
LAMPT = {
	UNKNOWN: -1,
	OK: 0,
	WARNING: 1,
	CRITICAL: 2,
	SKIPPED: 3,
	tests: {},
	loadTests: function() {
		var http = new HTTPClient();
		http.setMethod(http.GET);
		http.setURL('?action=gettests');
		http.onDone(LAMPT.onTestsLoaded);
		http.send();
	},
	onTestsLoaded: function(http) {
		var data = JSON.parse(http.responseText);
		for (var test in data) {
			if (data.hasOwnProperty(test)) {
				if (LAMPT.tests[data[test]['category']] == undefined) {
					LAMPT.tests[data[test]['category']] = {};
				}
				LAMPT.tests[data[test]['category']][test] = data[test];				
			}
		}
		
		var testlist = document.getElementById('testlist');
		var tr;
		for (var category in LAMPT.tests) {
			if (LAMPT.tests.hasOwnProperty(category)) {
				var heading = document.createElement('h2');
				var htext = document.createTextNode(category);
				heading.appendChild(htext);
				testlist.appendChild(heading);
				var table = document.createElement('table');
				tr = document.createElement('tr');
				
				var emptyth = document.createElement('th');
				var emptrythtext = document.createTextNode(' ');
				emptyth.appendChild(emptrythtext);
				tr.appendChild(emptyth);

				var testnameth = document.createElement('th');
				var testnamethtext = document.createTextNode('Name');
				testnameth.appendChild(testnamethtext);
				tr.appendChild(testnameth);

				var testdescth = document.createElement('th');
				var testdescthtext = document.createTextNode('Description');
				testdescth.appendChild(testdescthtext);
				tr.appendChild(testdescth);

				table.appendChild(tr);
				var cbtd;
				var cb;
				var nametd;
				var namelabel;
				var nametext;
				var desctd;
				var desctext;
				for (var testid in LAMPT.tests[category]) {
					if (LAMPT.tests[category].hasOwnProperty(testid)) {
						tr = document.createElement('tr');

						cbtd = document.createElement('td');
						cbtd.style.align = 'center';
						cbtd.style.valign = 'middle';
						cb = document.createElement('input');
						cb.type = 'checkbox';
						cb.id = testid;
						cb.name = testid;
						cb.checked = true;
						cbtd.appendChild(cb);
						tr.appendChild(cbtd);

						nametd = document.createElement('td');
						namelabel = document.createElement('label');
						namelabel.htmlFor = testid;
						nametext = document.createTextNode(LAMPT.tests[category][testid].name);
						namelabel.appendChild(nametext);
						nametd.appendChild(namelabel);
						tr.appendChild(nametd);

						desctd = document.createElement('td');
						desctext = document.createTextNode(LAMPT.tests[category][testid].description);
						desctd.appendChild(desctext);
						tr.appendChild(desctd);
						
						table.appendChild(tr);
					}
				}
				testlist.appendChild(table);
			}
		}
		
		var button = document.createElement('button');
		button.textContent='Start tests';
		button.addEventListener('click', LAMPT.startTests, false);
		testlist.appendChild(button);
	},
	startTests: function() {
		
	}
}

LAMPT.loadTests();