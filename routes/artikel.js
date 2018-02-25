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

/* GET users listing. */
router.get('/:id?', checkSchema(validator.paginasi) ,asing(async(req, res, next)=> {
	let id = req.params.id;
	let limit = req.query.limit || null;
	let offset = req.query.offset || 0;
	let error = validationResult(req)
	let json_query = { //base query
		$select : {
			$columns : field,
			$from : tb
			}
		}
	let whereField = {} //kolom where
	let whereValue = [] //nilai kolom where
	let orderField = {} //kolom order by
	let orderFieldTmp = req.query.orderBy || [] //nilai orderby dari query url orderBy
	let whereFieldTmp = _.intersection(field, Object.keys(req.query)) //membandingkan daftar kolom yang ada dengan kolom yang ada di query url, dan ambil kolom where yang terpanggil
	
	if(whereFieldTmp.length != 0){ //jika kolom where ada
		_.each(whereFieldTmp, (v)=>{ //iterasi kolom tersebut (array) (field1)
			whereField[v] = req.query[v] //menjadikan query url tadi menjadi object {field1 : value1}
			whereValue.push(req.query[v]) //menambahkan value dari where ke sini untuk dimasukan ke knex.raw
			})
		json_query.$select.$where = whereField //tambahkan hasil object where ke base query
		}
	if(orderFieldTmp.length != 0){  //jika kolom orderby ada
		if(orderFieldTmp instanceof Array){ //cek apakah kolom orderby satu atau lebih (array)
			_.each(orderFieldTmp, (v)=>{ //jika lebih dari satu atau array, iterasi
				let tmp = _.split(v, ':', 2) //pecah nilai query url order by "field:desc"
				orderField[tmp[0]] = tmp[1] //mengubah query url orderby tadi jadi object {field:desc}
				})
		}else{
			let tmp = _.split(orderFieldTmp, ':', 2) //jika tidak array atau satu, langsung pecah nilainya orderBy=field:desc
			orderField[tmp[0]] = tmp[1] //mengubah query url orderby tadi jadi object {field:desc}
		}
		json_query.$select.$sort = orderField //masukkan hasil object order ke base query
	}
	json_query.$select.$limit = limit
	json_query.$select.$offset = offset
	console.log(json_query)
	if(!error.isEmpty()){ //validasi nilai limit dan offset untuk paginasi
		res.status(422).json({error : error.mapped()})
		}
	else{
		let status;
		let query = {
			count : id ? db(tb).count(pk + ' as jumlah').where(pk, id) : db(tb).count(pk + ' as jumlah'),
			select : id ? db(tb).select().where(pk, id) : db.raw(sqlbuilder.build(json_query).sql,whereValue) // hasil akhir query
			}
		let row = await query.count; //Hitung total record
		let record = await query.select; //Record yang diambil
		row[0].jumlah == 0 ? status = 204 : status = 200; //http code
		res.status(status).json({data : record,record_total:row[0].jumlah});
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
