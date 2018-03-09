var express = require('express');
var router = express.Router();
let asing = require('../catchErr');
let tablePk = 'nidn';
let table = 'tbdosen';
let field = ['id_artikel','judul','isi','status','tgl_posting','id_user']
let defaultSort = tablePk+'|asc'
let validator = require('../validatorRule');
let validasiData = validator.artikel
const { checkSchema, validationResult } = require('express-validator/check');
const SQLBuilder = require('json-sql-builder');
var sqlbuilder   = new SQLBuilder('mysql');
let _ = require('lodash')
let fullTextSearch = 'nidn,nm_dosen'
let qs = require('qs')
/* GET users listing. */
router.get('/:id?', checkSchema(validator.paginasi) ,asing(async(req, res, next)=> {
	let id =req.params.id
	let per_page = req.query.per_page;
	let page = req.query.page;
	let sort = req.query.sort;
	let search = req.query.search;
	let data = {
		pagination : {},
		data : {}
	};
	let current_url = req.protocol + '://' + req.get('host') + req.originalUrl;
	current_url = current_url.split('?');
	current_url = current_url[0]
	let query = {}
	if(search) query = db.select().where(db.raw('MATCH ('+fullTextSearch+') AGAINST ("'+search+'")'));
	else query = db.select();
	
	queryCount = Object.assign(query); // Clone query sql untuk dipakai menghitung jumlah record
	let totalPage = await queryCount.from(table)
	totalPage = totalPage.length;
	data.pagination.total = totalPage;
	data.pagination.next_page_url = null;
	data.pagination.prev_page_url = null;
	data.pagination.per_page = null;
	data.pagination.current_page = 1;
	data.pagination.from = null;
	data.pagination.to = null;
	data.pagination.last_page = null;
	if(id){
		data.data = await query.from(table).where(tablePk, id);
	}else{
		if(sort) {
			let sortTmp = sort.split('|');
			let sortType = sortTmp[1].slice(0,4);
			query.orderBy(sortTmp[0], sortType);
		}			
		if(!per_page && !page){
			data.data = await query.from(table);
		}else{
			if(per_page && !page) {
				page = 1;
				per_page = Number(per_page);
			}
			else if(!per_page && page){
				per_page = 10;
				page = Number(page);
			}
			let offset = Number(per_page) * (Number(page) - 1);
			totalPage = totalPage.length
			data.data = await query.from(table).limit(per_page).offset(offset);
			data.pagination.total = totalPage;
			let lastPage = Math.ceil(totalPage/per_page);
		
			let queryStringNext = {
				per_page : per_page,
				page : page + 1 > lastPage ? null : page+1,
				sort : sort
			}
			let queryStringPrev = {
				per_page : per_page,
				page : page - 1 == 0 ? null : page-1,
				sort : sort
			}
			data.pagination = {
				next_page_url : queryStringNext.page == null ? null : current_url+'?'+qs.stringify(queryStringNext),
				prev_page_url : queryStringPrev.page == null ? null : current_url+'?'+qs.stringify(queryStringPrev),
				per_page : Number(per_page),
				current_page : Number(page),
				from : ((page-1)*per_page)+1,
				to : page*per_page,
				last_page : lastPage
			}
		}
	}
	res.status(200).json(data);
}))
module.exports = router;
