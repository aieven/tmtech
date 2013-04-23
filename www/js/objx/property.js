/*

 var o = {};

 OProperty(o, "name");

 o.setName       // setter
 o.getName       // getter
 o.name()        // getter
 o.name(val) // setter

 */


var OProperty = function(o, p, d) {

    // remember important names
    var _pName = "_" + p,
        _capName = OProperty.capName(p)
        ;

    if (typeof o[_pName] === "undefined") {
        o[_pName] = d || null;
    }

    // save existing getters and setters
    o["internal_get" + _capName] = o["get" + _capName] || function(){
        return this[_pName];
    };
    o["internal_set" + _capName] = o["set" + _capName] || function(value){
        this[_pName] = value;
    };

    // add explicit getter
    o["get" + _capName] = function(){

        if (arguments.length === 1 && arguments[0] !== OUnwrap) {
            if (arguments[0] instanceof Function) {
                arguments[0].apply(this, [this["internal_get" + _capName]()]);
                return this;
            } else if (arguments[0] === ONew) {
                return objx(this["internal_get" + _capName]());
            } else {
                OError("objx.property", arguments[0].toString() + " is not an acceptable argument to property getters.  Try either a function, ONew or nothing.");
            }
        } else {
            return this["internal_get" + _capName]();
        }
    };

    // add explicit setter
    o["set" + _capName] = function(v){
        this["internal_set" + _capName](v);
        return this;
    };


    // add shortcut method
    o[p] = function(){

        if (arguments.length === 0) {
            return this["get" + _capName].apply(this, arguments);
        } else if (arguments.length === 1) {

            if (arguments[0] === ONew || arguments[0] === OUnwrap || arguments[0] instanceof Function) {

                // getter
                return this["get" + _capName].apply(this, arguments);

            } else {

                // setter
                return this["set" + _capName].apply(this, arguments);

            }

        }

    };

    // add remove method
    o[p].remove = function(){

        // delete the property functions
        delete this[_pName];
        delete this["get" + _capName];
        delete this["set" + _capName];
        delete this[p];

    }.bind(o);

};


// converts a camelCase string to UpperCamelCase
OProperty.capName = function(n) {
    return n.substr(0,1).toUpperCase() + n.substr(1);
};


objx.fn.property = function(p, d) {
    OProperty(this.obj(), p, d);
};


OProvides("objx.property");