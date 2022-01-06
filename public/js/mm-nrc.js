/**
 * Myanmar National Registration Card Format Prefix
 *
 * Version: 0.1.0 (beta)
 * Language: JavaScript
 *
 * [State Number]\[District]([NAING])[Register No]
 *
 */

var MM_NUM = "\u1040-\u1049";
var MM_NUM_CHARS = "\u1040\u1041\u1042\u1043\u1044\u1045\u1046\u1047\u1048\u1049\u1040";
var mmChar = "\u1000\u1001\u1002\u1003\u1004\u1005\u1006\u1007\u1008\u1009\u100A\u100B\u100C\u100D\u100E\u100F\u1010\u1011\u1012\u1013\u1014\u1015\u1016\u1017\u1018\u1019\u101A\u101B\u101C\u101D\u101E\u101F\u1020\u1021\u1025\u1027";
var NAING_MM = "\u1014\u102D\u102F\u1004\u103A";
var regx_eng = /^[a-zA-Z0-9\/\(\)]+$/;
var regx_mm_num = new RegExp("^([" + MM_NUM + "]{1,6})\$");
var regx_mm = new RegExp("^([" + MM_NUM + "]{1,2})\/([" + mmChar + "]{3}|[" + mmChar + "]{6})\$");


var states = [
    { en: "Kachin", mm: "\u1000\u1001\u103B\u1004\u103A\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Kayah", mm: "\u1000\u101A\u102C\u1038\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Kayin", mm: "\u1000\u101B\u1004\u103A\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Chin", mm: "\u1001\u103B\u1004\u103A\u1038\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Sagaing", mm: "\u1005\u1005\u103A\u1000\u102D\u102F\u1004\u103A\u1038\u1010\u102D\u102F\u1004\u103A\u1038" },
    { en: "Tanintharyi", mm: "\u1010\u1014\u1004\u103A\u1039\u101E\u102C\u101B\u102E\u1010\u102D\u102F\u1004\u103A\u1038" },
    { en: "Bago", mm: "\u1015\u1032\u1001\u1030\u1038\u1010\u102D\u102F\u1004\u103A\u1038" },
    { en: "Magway", mm: "\u1019\u1000\u103D\u1031\u1038\u1010\u102D\u102F\u1004\u103A\u1038" },
    { en: "Mandalay", mm: "\u1019\u1014\u1039\u1010\u101C\u1031\u1038\u1010\u102D\u102F\u1004\u103A\u1038" },
    { en: "Mon", mm: "\u1019\u103D\u1014\u103A\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Rakhine", mm: "\u101B\u1001\u102D\u102F\u1004\u103A\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Yangon", mm: "\u101B\u1014\u103A\u1000\u102F\u1014\u103A\u1010\u102D\u102F\u1004\u103A\u1038" },
    { en: "Shan", mm: "\u101B\u103E\u1019\u103A\u1038\u1015\u103C\u100A\u103A\u1014\u101A\u103A" },
    { en: "Ayeyarwaddy", mm: "\u1027\u101B\u102C\u101D\u1010\u102E\u1010\u102D\u102F\u1004\u103A\u1038" }
];

// ref: http://en.wiktionary.org/wiki/Appendix:Unicode/Myanmar
var CHARACTERS = {
    // MM -> ENG
    "\u1000": "KA",
    "\u1001": "KHA",
    "\u1002": "GA",
    "\u1003": "GHA",
    "\u1004": "NGA",
    "\u1005": "SA",
    "\u1006": "HSA",
    "\u1007": "JA",
    "\u1008": "JHA",
    // TODO: NNYA
    "\u100A": "NYA",
    // "\u100A": "NNYA",
    "\u100B": "TTA",
    "\u100C": "TTHA",
    // "\u100D": "DDA",
    // "\u100E": "DDHA",
    // TODO: NNA
    "\u100F": "NNA",
    "\u1010": "TA",
    "\u1011": "HTA",
    "\u1012": "DA",
    "\u1013": "DHA",
    "\u1014": "NA",
    "\u1015": "PA",
    "\u1016": "PHA",
    "\u1017": "BA",
    "\u1018": "BHA",
    "\u1019": "MA",
    "\u101A": "YA",
    "\u101B": "RA",
    "\u101C": "LA",
    "\u101D": "WA",
    "\u101E": "THA",
    "\u101F": "HA",
    "\u1020": "LLA",
    "\u1021": "AH",
    "\u1025": "OU",
    "\u1027": "AE",
    // ENG -> MM
    "KA": "\u1000",
    "KHA": "\u1001",
    "GA": "\u1002",
    "GHA": "\u1003",
    "NGA": "\u1004",
    "SA": "\u1005",
    "HSA": "\u1006",
    "JA": "\u1007",
    "JHA": "\u1008",
    "NYA": "\u100A",
    // "NNYA": "\u100A",
    "TTA": "\u100B",
    "TTHA": "\u100C",
    // "DDA": "\u100D",
    // "DDHA": "\u100E",
    "NNA": "\u100F",
    "TA": "\u1010",
    "HTA": "\u1011",
    "DA": "\u1012",
    "DHA": "\u1013",
    "NA": "\u1014",
    "PA": "\u1015",
    "PHA": "\u1016",
    "BA": "\u1017",
    "BHA": "\u1018",
    "MA": "\u1019",
    "YA": "\u101A",
    "RA": "\u101B",
    "LA": "\u101C",
    "WA": "\u101D",
    "THA": "\u101E",
    "HA": "\u101F",
    "LLA": "\u1020",
    "AH": "\u1021",
    "OU": "\u1025",
    "AE": "\u1027"
};

/**
 * Constructor
 * {String} NRC String
 */

function MMNRC(nrc) {
    nrc = nrc.trim();
    nrc = nrc.replace(/\s/g, "");
    this.init.apply(this, arguments); //This simply calls init with the arguments from Foo
}

MMNRC.prototype.init = function(nrc, line) {
    const start = /\//;
    if (nrc.match(start) != null) {
        let nrc_no = nrc.split('/');
        this.state = nrc_no[0];
        this.dist = nrc_no[1].split('(')[0];
        this.num = nrc_no[1].split(')')[1];

        if ((this.match = regx_eng.exec(nrc))) {
            this.lang = "en";
            // 3 Characters Districts are not compete and can"t be generate Full Format
            if (this.dist.length === 3)
                this.inCompleteInfo = true;
            return this;
        } else if (this.match = regx_mm.exec(nrc.split('(')[0])) {
            this.lang = "mm";
            return this;
        }
    } else {
        this.inCompleteInfo = true;
        return this;
    }

    // Return for error
    toastr.error("Info - " + line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format")
    throw new Error("Type Not Match!");
}

MMNRC.prototype.isEqual = function(nrc) {
    return MMNRC.formatConvert(nrc).fullcode === this.getFormat();
}

MMNRC.prototype.init.prototype = MMNRC.prototype;

MMNRC.prototype.getFormat = function(lang = null, line = null) {
    // if (lang && lang === "mm" && !this.inCompleteInfo) {
    //     return MMNRC.toMyaNum(this.state) + "/" + MMNRC.mmConvDistrict(this.dist) + "(" + NAING_MM + ")" + MMNRC.toMyaNum(this.num);
    // } else if(lang && lang === "mm_only" && !this.inCompleteInfo){
    //     return this.state + "/" + this.dist + "(နိုင်)" + this.num;
    // }else {
    //     this.state = MMNRC.toEngNum(this.state, 10);
    //     this.dist = MMNRC.enConvDistrict(this.dist, 10);
    //     this.num = MMNRC.toEngNum(this.num, 10);

    //     return this.state + "/" + this.dist + "(N)" + this.num;
    // }

    if (lang && lang === "en" && !this.inCompleteInfo) {
        return this.state + "/" + this.dist + "(N)" + this.num;
    } else if (!this.inCompleteInfo) {
        if (this.lang && this.lang == "en" && !this.inCompleteInfo) {
            if (MMNRC.toMyaNum(this.state) != 'error') {
                var state = MMNRC.toMyaNum(this.state);
            } else {
                toastr.error("Info - " + line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format")
                throw new Error(line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format");
            }

            if (MMNRC.mmConvDistrict(this.dist) != 'error') {
                var dist = MMNRC.mmConvDistrict(this.dist);
            } else {
                toastr.error("Info - " + line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format")
                throw new Error(line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format");
            }

            if (MMNRC.toMyaNum(this.num) != 'error') {
                var num = MMNRC.toMyaNum(this.num);
            } else {
                toastr.error("Info - " + line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format")
                throw new Error(line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format");
            }

            return state + "/" + dist + "(" + NAING_MM + ")" + num;
        } else if (this.lang && this.lang == "mm") {
            return this.state + "/" + this.dist + "(နိုင်)" + this.num;
        }
    } else {
        toastr.error("Info - " + line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format")
    }

    throw new Error(line ? "line - " + line + " ၏ နိင်ငံသားမှတ်ပုံတင်အမှတ် မှားနေပါသည်။" : "Invalid NRC Format");
};

/**
 * Get State 
 */

MMNRC.prototype.getState = function(lang) {
    if (lang === "mm") {
        return states[this.dist].mm;
    } else {
        return states[this.dist].en;
    }
};

/**
 * Convert Myanmar Number type to English
 */
MMNRC.toEngNum = function(MM_NUM) {
    if (MM_NUM != undefined) {
        var _res = "";
        for (var i = 0; i < MM_NUM.length; i++) {
            _res += MM_NUM_CHARS.indexOf(MM_NUM[i]);
        }
        return parseInt(_res);
    } else {
        return 'error';
    }
};


MMNRC.toMyaNum = function(enNum) {
    if (enNum != undefined) {
        var _res = "";
        for (var i = 0; i < enNum.length; i++) {
            let index = enNum[i];
            _res += MM_NUM_CHARS[index];
        }
        return _res;
    } else {
        return 'error';
    }
};

MMNRC.mmConvDistrict = function(dist) {
    if (dist != undefined) {
        let District = "";
        dist_full = dist;

        for (let i = 0; i < dist_full.length; i++) {
            for (let j = 0; j < dist.length; j++) {
                check = dist_full.substr(0, j);
                if (check in CHARACTERS) {
                    District += check + ',';
                    dist_full = dist_full.substr(j);
                }
            }
        }

        dist = District.split(',');
        dist.splice(3);

        var _res = "";
        for (var i = 0; i < dist.length; i++) {
            let u = dist[i].toUpperCase();
            if (!CHARACTERS[u])
                return null;
            _res += CHARACTERS[u];
        }
        return _res;
    } else {
        return 'error';
    }
};

MMNRC.enConvDistrict = function(dist) {
    if (dist != undefined) {
        dist = dist.split('');
        var _res = "";
        for (var i = 0; i < dist.length; i++) {
            let u = dist[i];
            if (!CHARACTERS[u])
                return null;
            _res += CHARACTERS[u];
        }
        return _res;
    } else {
        return 'error';
    }
};

MMNRC.formatConvert = function(nrc) {
    var _res = {};
    var _match;
    if ((_match = regx_eng.exec(nrc))) {

        _res.lang = "en";
        _res.state += _match[1];
        _res.dist += parseInt(_match[2], 10);
        _res.number = parseInt(_match[3], 10);
        _res.fullcode = _res.state + "/" + _res.dist + "(N)" + _res.number;
        // 3 Characters Districts are not compete and can"t be generate Full Format
        if (_res.dist.length === 3)
            console.warn("Incomplete format!");
        return _res;
    } else if ((_match = regx_mm.exec(nrc))) {
        _res.lang = "mm";
        _res.state += MMNRC.toEngNum(_match[1], 10);
        _res.dist = MMNRC.convDistrict(_match[2], 10);
        _res.number = MMNRC.toEngNum(_match[3], 10);
        _res.fullcode = _res.state + "/" + _res.dist + "(N)" + _res.number;
        return _res;
    }
    return null;
};