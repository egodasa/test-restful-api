var express = require('express');
var router = express.Router();
let asing = require('../catchErr');
let pk = 'id_artikel';
let tb = 'tbartikel';
let field = ['id_artikel','judul','isi','status','tgl_posting','id_user']
let validator = require('../validatorRule');
let validasiData = validator.artikel
const { checkSchema, validationResult } = require('express-validator/check');
const SQLBuilder = require('json-sql-builder');
var sqlbuilder   = new SQLBuilder('mysql');
let _ = require('lodash')
let countAlias = 'COUNT(`'+pk+'`)'
/* GET users listing. */
router.get('/:id?', checkSchema(validator.paginasi) ,asing(async(req, res, next)=> {
	let id = req.params.id;
	let limit = req.query.limit;
	let page = req.query.page;
	let error = validationResult(req)
	let json_query = { //base query
			select : {
				$select : {
					$columns : field,
					$from : tb
					}
			},
			count : {
				$select : {
					$columns : [{$count : pk}],
					$from : tb
					}
			}
		}
	let respon = {data : {}} // set nilai awal respon/output json
	let whereField = {} //kolom where
	let whereValue = [] //nilai kolom where
	let orderField = {} //kolom order by
	let orderFieldTmp = req.query.sort || [] //nilai orderby dari query url sort
	let whereFieldTmp = _.intersection(field, Object.keys(req.query)) //membandingkan daftar kolom yang ada dengan kolom yang ada di query url, dan ambil kolom where yang terpanggil
	
	if(whereFieldTmp.length != 0){ //jika kolom where ada
		_.each(whereFieldTmp, (v)=>{ //iterasi kolom tersebut (array) (field1)
			whereField[v] = {$like : '%'+req.query[v]+'%'} //menjadikan query url tadi menjadi object {field1 : value1}, ketentuan where like %value%
			})
		json_query.select.$select.$where = {$or : [whereField]} //tambahkan hasil object where ke base query
		json_query.count.$select.$where = {$or : [whereField]} //tambahkan hasil object where ke count query (total rows)
		}
		
	if(orderFieldTmp.length != 0){  //jika kolom orderby ada
		if(orderFieldTmp instanceof Array){ //cek apakah kolom orderby satu atau lebih (array)
			_.each(orderFieldTmp, (v)=>{ //jika lebih dari satu atau array, iterasi
				let tmp = _.split(v, '|', 2) //pecah nilai query url order by "field:desc"
				orderField[tmp[0]] = tmp[1] //mengubah query url orderby tadi jadi object {field:desc}
				})
		}else{
			let tmp = _.split(orderFieldTmp, '|', 2) //jika tidak array atau satu, langsung pecah nilainya orderBy=field:desc
			orderField[tmp[0]] = tmp[1] //mengubah query url orderby tadi jadi object {field:desc}
		}
		json_query.select.$select.$sort = orderField //masukkan hasil object order ke base query
		json_query.count.$select.$sort = orderField //masukkan hasil object order ke count query
	}
	
	if(limit){ //jika ada limit di url
		page = 1 //set nilai page jadi 1
		json_query.select.$select.$limit = Number(limit) //count query tidak pakai limit dan offset
		json_query.select.$select.$offset = Number(limit) * (page-1)
	}else{
		json_query.select.$select.$limit = null //kalau tidak ada limit, set jadi null dan offset 0
		json_query.select.$select.$offset = 0
		}
	
	let finalQuerySelect = sqlbuilder.build(json_query.select) //finalQuery.sql isi query, finalQuery.values untuk value yang aka di pass ke knex.raw(sql, value)
	let finalQueryCount = sqlbuilder.build(json_query.count) //finalQuery untuk count query
	
	if(!error.isEmpty()){ //validasi nilai limit dan offset untuk paginasi
		res.status(422).json({error : error.mapped()})
		}
	else{
		let status;
		let query = {
			count : id ? db(tb).count(pk).where(pk, id) : db.raw(finalQueryCount.sql, finalQueryCount.values),
			select : id ? db(tb).select().where(pk, id) : db.raw(finalQuerySelect.sql, finalQuerySelect.values) // hasil akhir query
			}
		let row = await query.count; //Hitung total record
		let record = await query.select; //Record yang diambil
		respon.data = record[0]
		if(limit){ //jika ada limit di url
			respon.paginate = {} //tambahkan properti paginate pada output json
			respon.paginate.page_count = Math.ceil(row[0][0][countAlias]/limit) //menghitung total halaman berdasarkan limit
			respon.paginate.current_page = page //set nilai page sekarang
			respon.paginate.next_page = page+1 <= respon.paginate.page_count ? page+1 : null //set halaman selanjutnya
			respon.paginate.prev_page = page-1 != 0 ? page-1 : null //set halaman sebelumnya
			}
		row[0] == 0 ? status = 204 : status = 200; //http code 204 jika data kosong
		res.status(status).json(respon); //hasil output json
	}
}));
router.post('/', checkSchema(validasiData), asing(async(req, res, next)=> {
	let data = req.body;
	const error = validationResult(req);
	if(!error.isEmpty()){
		res.status(422).json({error : error.mapped()})
		}
	else{
		let query = await db(tb).insert(data)
		res.status(200).json();
		}
}));
router.put('/:id', checkSchema(validasiData), asing(async(req, res, next)=> {
	let id = req.params.id;
	let data = req.body;
	const error = validationResult(req);
	if(!error.isEmpty()){
		res.status(422).json({error : error.mapped()})
		}
	else{
		let query = await db(tb).where(pk, id).update(data)
	}
	res.status(200).json();
}));
router.delete('/:id', asing(async(req, res, next)=> {
	let id = req.params.id;
	let status;
	let query = await db(tb).where(pk, id).del()
	res.status(200).json();
}));
module.exports = router;
