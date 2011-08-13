HTTPClient = function() {
	this._xhr = this._getXHR();
}
HTTPClient.prototype._xhr = undefined;
HTTPClient.prototype._method = "GET";
HTTPClient.prototype._url = "";
HTTPClient.prototype._data = "";
HTTPClient.prototype._callbacks = {
	open: [],
	headers: [],
	loading: [],
	done: [],
	successful: [],
	error: []
};

HTTPClient.prototype.GET = "GET";
HTTPClient.prototype.HEAD = "HEAD";
HTTPClient.prototype.POST = "POST";
HTTPClient.prototype.PUT = "PUT";
HTTPClient.prototype.DELETE = "DELETE";
HTTPClient.prototype.OPTIONS = "OPTIONS";

HTTPClient.prototype.setMethod = function(method) {
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
}

HTTPClient.prototype.setURL = function(url) {
	this._url = url;
}

HTTPClient.prototype.setData = function(data) {
	this._data = data;
}

HTTPClient.prototype.send = function() {
	this._xhr.open(this._method, this._url, true);
	this._xhr.onreadystatechange = this._callbackHandler;
	this._xhr._callbacks = this._callbacks;
	this._xhr._callback = this._callback;
	this._xhr.send(this._data);
}

HTTPClient.prototype.isError = function() {
	var re = new RegExp("/^4|5/");
	if (re.match(xhr.status)) {
		return true;
	} else {
		return false;
	}
}

HTTPClient.prototype.onOpened = function(callback) {
	this._callbacks.open.push(callback);
}

HTTPClient.prototype.onHeadersReceived = function(callback) {
	this._callbacks.headers.push(callback);
}

HTTPClient.prototype.onLoading = function(callback) {
	this._callbacks.loading.push(callback);
}

HTTPClient.prototype.onDone = function(callback) {
	this._callbacks.done.push(callback);
}

HTTPClient.prototype.onDoneFailed = function(callback) {
	this._callbacks.error.push(callback);
}

HTTPClient.prototype.onDoneSuccessful = function(callback) {
	this._callbacks.successful.push(callback);
}

HTTPClient.prototype._getXHR = function() {
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

HTTPClient.prototype._callback = function(func, xhr) {
	for (var i in this._callbacks[func]) {
		try {
			this._callbacks[func][i](xhr);
		} catch (e) {
			
		}
	}
}

HTTPClient.prototype._callbackHandler = function() {
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
