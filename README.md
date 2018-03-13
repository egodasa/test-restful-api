# What's Include?
1. Codeigniter 3.1.7
2. Several component, created with VueJS. Read more at <a href="https://github.com/egodasa/adminlte-vuejs-laravel-starter">adminlte-vuejs-laravel-starter</a> for the components
3. Example of RESTful API files (controllers/Dosen.php, Mahasiswa.php). See below for details.

# RESTful API section
I'm using <a href="https://github.com/chriskacerguis/codeigniter-restserver">codeigniter-restserver</a> to create the API.<br/>
You can see the example at controllers/Dosen.php

# Example
Access <b>http://localhost/api/dosen?per_page=5&page=2&sort=nidn|desc</b> and the response will be :

```javascript
{
    "status_code": 200,
    "errors": null,
    "data": [
        {
            "id_dosen": "319",
            "nm_dosen": "REVI GUSRIVA",
            "nidn": "1031088901",
            "status_dosen": "1"
        },
        {
            "id_dosen": "270",
            "nm_dosen": "RAHMAT HIDAYAT",
            "nidn": "1031059001",
            "status_dosen": "1"
        },
        {
            "id_dosen": "17",
            "nm_dosen": "HASMAYNELIS FITRI",
            "nidn": "1031057501",
            "status_dosen": "1"
        },
        {
            "id_dosen": "313",
            "nm_dosen": "NUGRAHA RAHMANSYAH",
            "nidn": "1031038901",
            "status_dosen": "1"
        },
        {
            "id_dosen": "116",
            "nm_dosen": "LAILA MARHAYATI",
            "nidn": "1031038803",
            "status_dosen": "1"
        }
    ],
    "pagination": {
        "total": 345,
        "next_page_url": "/api/dosen?per_page=5&page=3&sort=nidn%7Cdesc",
        "prev_page_url": "/api/dosen?per_page=5&page=1&sort=nidn%7Cdesc",
        "per_page": 5,
        "current_page": 2,
        "from": 6,
        "to": 10,
        "last_page": 69
    }
}
```

# Rules to access the API
Open localhost/api/dosen,
<br/>
It will give You all dosen list
<br/>
Open localhost/api/dosen/1234,
<br/>
It will give You dosen with NIDN = 1234
<br/>
You can add query string : <b>?per_page=10&page=3&sort=nm_dosen|asc&search=desi</b>
<ul>
	<li>per_page
	<br/>
	It will give You 10 data. Simply, it will give limit 10 to sql
	</li>
	<li>page<br/>
	Used to make the pagination. For 100 data and per_page set to 10, You can set page to 1 - 10
	</li>
	<li>sort<br/>
	Filter for the data. Only single field can be filtered
	</li>
	<li>search<br/>
	For search the data, with Full text search on sql.
	</li>
</ul>
