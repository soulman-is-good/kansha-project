process.title = 'node_grabber';
var http = require('http');
var qs = require('querystring');
var fs = require('fs');
//var jsdom = require('./tmpvar-jsdom/lib/jsdom');
fs.writeFileSync('ya.pid', ""+process.pid);
var port = 3435;
var proxy = false;
var host = (proxy?'kansha.no-ip.org':'market.yandex.kz');
var list_re = /<h3 class="b\-offers__title"><a.+?href="\/model\.xml\?modelid=([0-9]+)?\&amp\;hid=([0-9]+)?[^>]+?>(.+?)<\/a>/g;
var ctable_re = /<tbody class="l\-compare__body">([\s\S]+?)<\/tbody>/m;
var ctr_re = /<tr[^>]+?id="([^"]+?)"[^>]*>(.+?)<\/tr>/g;
var c_re = /<td class="l\-compare__name"><div class="l\-compare__name__i">([\s\S]+?)<\/div><\/td><td class="l\-compare__g"><div class="l\-compare__g__i"><\/div><\/td><td class="l\-compare__model">([\s\S]+?)<\/td>/gm;
var get_title_re = /<a href="\/model\.xml\?modelid=([0-9]+?)\&amp\;hid=([0-9]+?)" class="b\-compare__model__link">([\s\S]+?)<\/a>/g;
var prop_group_re = /<tr><th colspan="2" class="b\-properties__title">([^<]+)<\/th><\/tr>/gm;
var name_re = /<h1 class="b-page-title[^"]*">([\s\S]+?)<\/h1>/; 
var imgtable_re = /<div class="b-model-microcard__img">(.+?)<\/div>/;
var cimg_re = /<img .*?src="([^"]+?)"[^>]+?class="b\-compare__img"/g
var img_re = /<img .*?src="([^"]+)"/g
var iimg_re = /<span class="b-model-pictures__big"><a target="_blank" href="([^"]+)" id="([^"]+)"/g
var bigimg_re = /<td class="bigpic"><a href="(http\:\/\/mdata\.yandex\.net\/i\?path=.+?)"/
var group_re = /<div class="b-breadcrumbs"><a class="b-breadcrumbs__link" href="\/catalog\.xml\?hid=([0-9]+)[^"]*">([^<]+)<\/a>/
//var trans_data = eval(fs.readFileSync('ya.conf', 'utf8'));
//var ya = http.createClient(80, host);
var no_proxy_delay = true;
http.createServer(function(req, res) {
	try {
		r = require('url').parse(req.url, true);
		if (typeof r.query == 'undefined') {
			res.writeHead(200, {'Content-type' : 'text/plain'});
			res.write('');
			res.end();
			return;
		}
		if (typeof(r.query.nodelay) != 'undefined') {
			no_proxy_delay = true;
		} else {
			no_proxy_delay = false;
		}
		if (typeof(r.query.test) != 'undefined'){
			res.writeHead(200, {'Content-type' : 'text/plain'});
			res.write("Success!",'utf8');
			res.end();
			return;
		}
		if (typeof(r.query.fetch) != 'undefined'){
			var query = r.query.q;
			findItems(query, function(err, data) {
				if(data.length == 1){
					data = data.pop();
					query = 'hid='+data.hid+'&CMD=-CMP='+data.modelid;
					fetchItemSpecs(query, function(err, data) {
						res.writeHead(200, {'Content-type' : 'text/plain'});
						var str = JSON.stringify(data);
						res.write(str,'utf8');
						res.end();
					});
				}
				else{
					res.writeHead(200, {'Content-type' : 'text/plain'});
					res.write("[]",'utf8');
					res.end();				
				}
			});
			return;
		}else
		if (typeof(r.query.find) != 'undefined'){
			var query = r.query.q;
			findItems(query, function(err, data) {
				res.writeHead(200, {'Content-type' : 'text/plain'});
				var str = JSON.stringify(data);
				res.write(str,'utf8');
				//fs.writeFileSync('last_grab.log', '('+str+')');
				res.end();
			});
		}else{
			//query = 'modelid=' + r.query.modelid+'&hid='+r.query.hid;
			query = 'CMD=-CMP=';
			fetchItemSpecs(query, r.query.modelid, function(err, data) {
				res.writeHead(200, {'Content-type' : 'text/plain'});
				var str = JSON.stringify(data);
				res.write(str,'utf8');
				//fs.writeFileSync('last_grab.log', '('+str+')');
				res.end();
			});
		}
	} catch(e) {
		console.log("Catched exception " + e);
	}	
}).listen(port);
console.log("Server started");

function findItems(query, cb) {
	url = '/search.xml?text=' + encodeURIComponent(unescape(query)) + '&nopreciser=1';	
	url = (proxy?'/proxy/proxy.php?' + require('querystring').stringify({'q':'http://market.yandex.kz'+url, 'nodelay':no_proxy_delay?'1':'0'}):url);
	console.log('searching '+url)
	http.get({'host':host,'port':80,'path':url},function(resp){
		resp.setEncoding('utf8');
		var data = '';
		resp.on('data', function (chunk) {
			data += chunk;		
		});
		resp.on('end', function() {
			if (data == '') {
				findItems(query, cb);
				return;
			}
			var ar = [];
			var some = [];
			while(some = list_re.exec(data)) {
				ar.push({'text':striptags(some[3]),'modelid':some[1],'hid':some[2]});
			}		
			list_re.lastIndex = 0;
			cb(null,ar);
			//asyncMap(ar, function(some, cb) {
			//	handle_one(some, cb);
			//}, cb);
		});
		resp.on('close', function() {
			//findItems(query, cb);
		});
	});
}

function fetchItemSpecs(query, iid, cb) {
	url = '/compare.xml?'+query+iid;	
	url = (proxy?'/proxy/proxy.php?' + require('querystring').stringify({'q':'http://market.yandex.kz'+url, 'nodelay':no_proxy_delay?'1':'0'}):url);
	var mysql = require('mysql');
	var manss = [];
	var connection = mysql.createConnection({
	  host     : 'localhost',
	  user     : 'root',
	  password : 'root'
	});

	connection.connect();

	connection.query('SELECT title,name FROM kansha_tmp.manufacturer',
	function(err, rows, fields) {
	  if (err) {console.log(err);return false;}
	  manss = rows;
	});

	if(connection) connection.end();	
	http.get({'host':host,'port':80,'path':url},function(re){
		re.setEncoding('utf8');
		var data = '';
		re.on('data', function (chunk) {
			data += chunk;				
		});
		re.on('end', function() {
			var ar = {};
			var group = [];
			var current = '';
			var img = cimg_re.exec(data);
                        http.get({'host':host,'port':80,'path':'/model.xml?modelid='+iid},function(ire){
                            ire.setEncoding('utf8');
                            var idata = '';
                            ire.on('data', function (chunk) {
                                    idata += chunk;				
                            });
                            ire.on('end', function() {
                                var timg = iimg_re.exec(idata);
                                img[0] = timg;
                                if(timg){
                                    var ilnk = timg[1];
                                    var iid = timg[2];
                                    ilink = ilnk.split('_')
                                    var tmp = ilink.pop();
                                    ilink.push(iid+'.'+tmp.split('.').pop());
                                    img[1] = ilink.join('_');
                                }
                            });
                        });
			cimg_re.lastIndex = 0;
			var title = get_title_re.exec(data);
			get_title_re.lastIndex = 0;
			if(title == null) return;
			ar['modelid'] = title[1];
			ar['hid'] = title[2];
			ar['title'] = title[3];
			for(m in manss)
			    if(ar['title'].toLowerCase().indexOf(manss[m].title.toLowerCase())!=-1)
				ar['manname'] = manss[m].name;
			ar['image'] = img;
			ar['articule'] = '';
			if(table = ctable_re.exec(data)) {				
				while(tr = ctr_re.exec(table[1])) {			
					var id = tr[1];
					if(val = c_re.exec(tr[2])){
						ar[id] = {};
						var key = trim(striptags(val[1]));
						var value = trim(striptags(val[2]));
						switch(value){
							case "—":
								ar[id]['type'] = 'boolean';
								value = 0;
							break;
							case "•":
								ar[id]['type'] = 'boolean';
								value = 1;
							break;							
							default:
								if((/^\-\d+?[\.|,]\d+$|\d+?[\.|,]\d+$/).test(value)){
									ar[id]['type'] = 'decimal';
									value = value.replace(',','.');
								}else if((/^\-\d+$|^\d+$/).test(value))
									ar[id]['type'] = 'integer';
								else
									ar[id]['type'] = (value.length<128)?'string':'content';
						}						
						ar[id]['status'] = '1';						
						ar[id]['original'] = key;
						ar[id]['label'] = key;
						ar[id]['value'] = value;
						ar[id]['isnew'] = 1;
					}					
					c_re.lastIndex = 0;
				}	
				ctr_re.lastIndex = 0;			
			}		
			ctable_re.lastIndex = 0;
			cb(null,ar);
		});
		re.on('close', function() {
			findItems(query, cb);
		});
	});
}

function striptags(s) {
	return s.replace(/<\/?([a-z][a-z0-9]*)\b[^>]*>/gi, '');
}

function trim(string)
{
  return string.replace(/(^\s+)|(\s+$)/g, "");
}

function handle_one(id, cb) {
	while(true) {
		try {

			url = '/model-spec.xml?modelid=' + id[1] + '&hid=' + id[2];
			var rr = ya.request('GET', (proxy?'/proxy/proxy.php?' + require('querystring').stringify({'q':'http://market.yandex.kz'+url, 'nodelay':no_proxy_delay?'1':'0'}):url), {'host' : host});
			rr.end();
			rr.on('response', function(resp) {
				resp.setEncoding('utf8');
				var data = '';
				resp.on('data', function(chunk) {
					data+= chunk;
				});
				resp.on('end', function() {
					console.log("handle " + id);
					if (data == '') {
						console.log("try once more");
						handle_one(id, cb);
						return;
					}
					try {
						var group_id = 0;
						var group_name = '';
						if (group_re.test(data)) {
							var __e = group_re.exec(data);
							group_id = __e[1]; 
							group_name = __e[2];
						}
						var name = striptags(name_re.exec(data)[1]);
						name = name.replace("новинка", "");
						name = name.replace("  ", " ");
						name = trim(name);
						if (imgtable_re.test(data)) {
							imgdata = imgtable_re.exec(data)[1];
						} else {
							imgdata = '';
						}
						imgs = [];
						while(i = img_re.exec(imgdata)) {
							if (i[1].substr(0, 7) == 'http://') {
								if (i[1].substr(i[1].length-11) == "&amp;size=1") {
									i[1] = i[1].substr(0, i[1].length-11);
								}
								imgs.push(i[1]);
							}
						}
						if (i = bigimg_re.exec(imgdata)) {
							imgs.push(i[1]);
						}
						img_re.lastIndex = 0;
						if (data = ctable_re.exec(data)) {
							data = data[1];
						} else {
							data = '';
						}
					} catch(e) {
						console.log("Catched exception " + e + "\nAt id " + id[1] + "\nData is " + data);
						console.log("Data is '" + data + "'");
						process.exit(1);
					}
					var chs = {'name': name, 'images':imgs, 'id':id[1], 'group_id':group_id, 'group_name': group_name};
					var fields = [{'name': 'name', 'data': name, 'group': false}];
					var pg = prop_group_re.exec(data);
					pg = pg?pg[1]:false;
					var pg_next = prop_group_re.exec(data);
					pg_next = pg_next?pg_next[1]:false;
					while(c = c_re.exec(data)) {
						while (pg && (pg_next && (c_re.lastIndex > prop_group_re.lastIndex))) {
							pg = pg_next;
							pg_next = prop_group_re.exec(data);
							pg_next = pg_next?pg_next[1]:false;
						}
						fields.push({'name': c[1], 'data': c[2], 'group': pg});
					}
					prop_group_re.lastIndex = 0;
					for(__i in fields) {
						var cname = fields[__i].name;
						var cdata = fields[__i].data;
						var prop_group = fields[__i].group;
						if ((typeof(cdata) == 'undefined') || (cdata == null)) {
							continue;
						}
						if (typeof(trans_data.keys[cname]) == 'undefined') {
							continue;
						}
						try{
						for(i = 0; i < trans_data.keys[cname].length; i++) {
							cid = trans_data.keys[cname][i];
							if ((cid != null) && (typeof(cid) == 'object')) {
								if ((typeof(cid['group']) != 'undefined') && (cid.group) && (cid.group != prop_group)) {
									continue;
								} else {
									cid = cid.cid;
								}
							}
							ncdata = cdata;
							namedata = ncdata;
							if (typeof(trans_data.vals[cid]) != 'undefined') {
								switch(trans_data.vals[cid].type) {
									case 'regex': {
										var re = RegExp(trans_data.vals[cid].regex);
										if (re.test(cdata)) {
											ncdata = re.exec(cdata)[trans_data.vals[cid].regex_id];
											namedata = ncdata;
										} else {
											namedata = ncdata = "";
										}
										break;
									}
									case 'list': {
										if (typeof(trans_data.vals[cid].list[cdata.toLowerCase()]) != 'undefined') {
											ncdata = trans_data.vals[cid].list[cdata.toLowerCase()];
											namedata = ncdata;
										}
										break;
									}
									default: {

										//console.log(cid);
										//console.log(JSON.stringify(trans_data.vals[cid].list_options));
										if ((typeof(trans_data.vals[cid].list_options)!='undefined')) {
											var _l = false;
											for(_i in trans_data.vals[cid].list_options) {
												_l = true;
												break;
											}
											if (!_l) {
												break;
											}
											if (typeof(chs[cid]) != 'undefined') {
												ncdata = chs[cid];
											} else {
												ncdata = [];
											}
											_pos = {};
											for(_i in trans_data.vals[cid].list_options) {
												var list_option_array = trans_data.vals[cid].list_options[_i];
												if (typeof(trans_data.vals[cid].list_options[_i]) != 'object') {
													list_option_array = [trans_data.vals[cid].list_options[_i]];
												}
												for(__zi in list_option_array) {
													_v = list_option_array[__zi].toLowerCase();
													if (_v == "") {
														continue;
													}
													_srch1 = cname.toLowerCase();
													_srch2 = cdata.toLowerCase();
													_lp = 0;
													_lp2 = 0;
													var _it = 0;
													while(true) {
														_it++;
														if (_it > 10000) {
															console.log("fuck at "+JSON.stringify(trans_data.vals[cid].list_options[_i]));
															process.exit(1);
														}
														_lp = 1 + (_sp = _srch1.indexOf(_v, _lp));
														_lp2 = 1 + (_sp2 = _srch2.indexOf(_v, _lp2));
														__f = 0;
														if (_lp == 0) {
															_lp = _srch1.length;
															__f++;
														}
														if (_lp2 == 0) {
															_lp2 = _srch2.length;
															__f++;
														}
														if (__f == 2) {
															break;
														}
														if (((_sp != -1) && (cdata != 'нет')) || (_sp2 != -1)) {
															p = _sp;
															if (_sp == -1) {
																p = _sp2;
															}
															var _fl = false;
															for(_j in trans_data.vals[cid].list_options) {
																var _list_option_array = trans_data.vals[cid].list_options[_j];
																if (typeof(_list_option_array) != 'object') {
																	_list_option_array = [_list_option_array];
																}
																for(__zi2 in _list_option_array) {
																	_v2 = _list_option_array[__zi2].toLowerCase();
																	_pos2 = _v2.indexOf(_v);
																	if (_pos2 != -1) {
																		if ((typeof(_pos[p-_pos2]) != 'undefined') && ((_pos[p-_pos2] == _j))) {
																			_fl = true;
																			break;
																		}
																	}
																}
																if (_fl) {
																	break;
																}
															}
															if (_fl) {
																continue;
															}
															if ((typeof(_pos[p]) != 'undefined')) {
																//console.log(_i + "at the same pos as "+_pos[p]+" ["+p+"]");
																var _list_option_array = trans_data.vals[cid].list_options[_pos[p].first];
																if (typeof(_list_option_array) != 'object') {
																	_list_option_array = [_list_option_array];
																}
																if (_list_option_array[_pos[p].second].length < _v.length) {
																	for(ri = 0; ri < ncdata.length; ri++) {
																		if (ncdata[ri] == _pos[p].first) {
																			ncdata[ri] = _i;
																			_pos[p].first = _i;
																			_pos[p].second = __zi;
																			break;
																		}
																	}
																	continue;
																}
															}
															_pos[p] = {'first': _i, 'second': __zi};
															if (ncdata.length) {
																var _list_option_array = trans_data.vals[cid].list_options[ncdata[0]];
																if (typeof(_list_option_array) != 'object') {
																	_list_option_array = [_list_option_array];
																}
																if (_v.length > _list_option_array[0].length) {
																	ncdata.push(ncdata[0]);
																	ncdata[0] = _i;
																} else {
																	ncdata.push(_i);
																}
															} else {
																ncdata.push(_i);
															}
														}
													}
												}
											}
											if (ncdata.length > 0) {
												namedata = trans_data.vals[cid].list_options[ncdata[0]];
											}
										}
										break;
									}
								}
							}
							if (typeof(chs.strs) == 'undefined') {
								chs.strs = {};
							}
							if (typeof(chs.orig_strs) == 'undefined') {
								chs.orig_strs = {};
							}
							chs.strs[cid] = namedata;
							if (typeof(chs.orig_strs[cid]) != 'undefined') {
								chs.orig_strs[cid] += ' ' + cdata;
							} else {
								chs.orig_strs[cid] = cdata;
							}
							chs[cid] = ncdata;
						}
							} catch(e) {
								console.log("error " + e);
								console.log("cdata = " + JSON.stringify(cdata) + "typeof = " + typeof(cdata));
							}
					}
					for(i in trans_data.use_for_name) {
						var nm = '';
						for(j in trans_data.use_for_name[i]) {
							if (j != 0) {
								if (typeof(chs[trans_data.use_for_name[i][j]]) != 'undefined') {
									nm += ' ' + chs[trans_data.use_for_name[i][j]];
								}
							}
						}
						if (nm) {
							chs['_name_'+i] = nm;
						}
					}
					cb(null, chs);
					//console.log(JSON.stringify(chs));
					//c_re.lastIndex = 0;
				});
			});
			break;
		} catch(e) {
			//nothing...
		}
	}
}

function asyncMap (list, s, cb_) {
	if (typeof cb_ !== "function") throw new Error(
		"No callback provided to asyncMap");
	var data = []
		, errState = null
		, l = list.length;
	if (!l) return cb_(null, [])
	function cb (er, d) {
		if (errState) return;
		if (arguments.length > 1) data = data.concat(d);
		if (er) return cb_(errState = er, data);
		else if (-- l === 0) cb_(errState, data);
	}
	list.forEach(function (ar) {
		if (typeof s == 'function') {
			s(ar, cb);
		} else if (Array.isArray(s)) {	
			var obj = null
			  , fn = s.shift();
			if (typeof f === "object") {
			  // [obj, "method", some, args]
			  obj = f;
			  fn = obj[s.shift()];
			}
			if (typeof fn !== "function") throw new Error(
			  "Invalid function in asyncMap: "+typeof(fn));
			fn.apply(obj, s.concat(cb));		
		} else {
			throw new Error(
				"Invalid function in asyncMap: "+fn);
		}
	});
}
