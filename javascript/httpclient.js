HTTPClient = function() {
	this._method = 'GET';
	this._url = "";
	this._data = "";
	this._callbacks = {
		open: [],
		headers: [],
		loading: [],
		done: [],
		successful: [],
		error: []
	};
	this.GET = "GET";
	this.HEAD = "HEAD";
	this.POST = "POST";
	this.PUT = "PUT";
	this.DELETE = "DELETE";
	this.OPTIONS = "OPTIONS";

	this.setMethod = function(method) {
		switch (method) {
			case this.GET:
			case this.HEAD:
			case this.POST:
			case this.PUT:
			case this.DELETE:
			case this.OPTIONS:
				this._method = method;
				break;
			default:
				throw "Invalid HTTP method: " + method;
		}
	};

	this.setURL = function(url) {
		this._url = url;
	};

	this.setData = function(data) {
		this._data = data;
	};

	this.send = function() {
		this._xhr.open(this._method, this._url, true);
		this._xhr.onreadystatechange = this._callbackHandler;
		this._xhr._callbacks = this._callbacks;
		this._xhr._callback = this._callback;
		this._xhr.isError = this.isError;
		this._xhr.send(this._data);
	};

	this.isError = function() {
		var re = new RegExp("/^(4|5)/");
		if (re.exec(this.status)) {
			return true;
		} else {
			return false;
		}
	};

	this.onOpened = function(callback) {
		this._callbacks.open.push(callback);
	};

	this.onHeadersReceived = function(callback) {
		this._callbacks.headers.push(callback);
	}

	this.onLoading = function(callback) {
		this._callbacks.loading.push(callback);
	}

	this.onDone = function(callback) {
		this._callbacks.done.push(callback);
	}

	this.onDoneFailed = function(callback) {
		this._callbacks.error.push(callback);
	}

	this.onDoneSuccessful = function(callback) {
		this._callbacks.successful.push(callback);
	}

	this._getXHR = function() {
		var xhr;
		try {
			xhr = new XMLHttpRequest();
		} catch (e) {
			try {
				xhr = new ActiveXObject('MSXML2.XMLHTTP.3.0');
			} catch (e) {
				throw "No HTTP client library available!";
			}
		}
		return xhr;
	};

	this._callback = function(func, xhr) {
		for (var i in this._callbacks[func]) {
			try {
				this._callbacks[func][i](xhr);
			} catch (e) {

			}
		}
	}

	this._callbackHandler = function() {
		switch (this.readyState) {
			case 1:
				this._callback('open', this);
				break;
			case 2:
				this._callback('headers', this);
				break;
			case 3:
				this._callback('loading', this);
				break;
			case 4:
				this._callback('done', this);
				if (this.isError()) {
					this._callback('error', this);
				} else {
					this._callback('successful', this);
				}
				break;
		}
	}

	this._xhr = this._getXHR();
}
