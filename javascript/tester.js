
LAMPT = {
	UNKNOWN: -1,
	OK: 0,
	WARNING: 1,
	CRITICAL: 2,
	SKIPPED: 3,
	tests: {},
	testqueue: [],
	currentTest: '',
	loadTests: function() {
		if (JSON == undefined) {
			alert('This browser does not support native JSON and is therefore unsupported!');
		}
		
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
				emptyth.className = 'cb';
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
				var descp;
				var desctext;
				var morea;
				var moretext;
				for (var testid in LAMPT.tests[category]) {
					if (LAMPT.tests[category].hasOwnProperty(testid)) {
						tr = document.createElement('tr');

						cbtd = document.createElement('td');
						cbtd.id = 'td-' + testid;
						cbtd.className = 'cb';
						cbtd.style.textAlign = 'center';
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
						desctd.id = 'desc-' + testid;
						descp = document.createElement('p');
						desctext = document.createTextNode(LAMPT.tests[category][testid].description + ' ');
						descp.appendChild(desctext);
						morea = document.createElement('a');
						morea.target='_blank';
						morea.href=LAMPT.tests[category][testid].link;
						moretext = document.createTextNode('Read more!');
						morea.appendChild(moretext);
						descp.appendChild(morea);
						desctd.appendChild(descp);
						tr.appendChild(desctd);
						
						table.appendChild(tr);
					}
				}
				testlist.appendChild(table);
			}
		}
		
		var button = document.createElement('button');
		button.id = 'testbutton';
		button.textContent='Start tests';
		button.addEventListener('click', LAMPT.startTests, false);
		button.onclick='return false;';
		testlist.appendChild(button);
	},
	startTests: function() {
		document.getElementById('testbutton').disabled=true;
		LAMPT.testqueue = new Array();
		for (var category in LAMPT.tests) {
			if (LAMPT.tests.hasOwnProperty(category)) {
				for (var testid in LAMPT.tests[category]) {
					if (LAMPT.tests[category].hasOwnProperty(testid)) {
						if (document.getElementById(testid).checked == true) {
							LAMPT.testqueue.push(testid);
							document.getElementById('td-' + testid).innerHTML = '';
						}
					}
				}
			}
		}
		LAMPT.nextTest();
	},
	nextTest: function() {
		var nextTest = LAMPT.testqueue.shift();
		if (nextTest) {
			LAMPT.currentTest = nextTest;
			var nextTestTd = document.getElementById('td-' + nextTest);
			nextTestTd.innerHTML = '<img src="images/progress.gif" />';
			var xhr = new HTTPClient();
			xhr.setMethod(xhr.GET);
			xhr.setURL('?action=runtest&test=' + nextTest);
			xhr.onDoneFailed(LAMPT.nextTest);
			xhr.onDoneSuccessful(LAMPT.nextTestResult);
			xhr.send();
		}
	},
	nextTestResult: function(xhr) {
		try {
			var result = JSON.parse(xhr.responseText);
			var td = document.getElementById('td-' + LAMPT.currentTest);
			switch (result.code) {
				case -1:
					td.innerHTML = '?';
					td.className='cb unknown';
					break;
				case 0:
					td.innerHTML = '+';
					td.className='cb ok';
					break;
				case 1:
					td.innerHTML = 'W';
					td.className='cb warning';
					break;
				case 2:
					td.innerHTML = 'E';
					td.className='cb error';
					break;
				case 3:
					td.innerHTML = 'S';
					td.className='cb skipped';
					break;
			}
			var desc = document.getElementById('desc-' + LAMPT.currentTest);
			desc.innerHTML = desc.innerHTML + result.description;
		} catch (e) {
		}
		LAMPT.nextTest();
	}
}

LAMPT.loadTests();