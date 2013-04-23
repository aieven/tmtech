/*
 *  class
 *
 *  Real object-orientation for JavaScript
 *
 *  OClass([base-class ,][mixin, ] class-definition);
 *
 *  
 *  base-class                          The base class
 *  mixin                                       An object to be mixed in
 *      class-definition                An object defining the class
 *
 */
var OClass = function() {


    var
        property,


        // create the constructor
        klass = function(){

            // call the "init" method
            this.init.apply(this, arguments);

        },

        // save the definition
        definition = arguments[arguments.length - 1],


        // hold any onClassReady delegates
        onClassReadyDelegates = null


        ;

    // set a default init method
    klass.prototype.init = function(){};

    // loop through each inherits argument
    for (var i = 0, l = arguments.length - 1; i < l; i++) {


        // get this item
        var item = arguments[i];

        // is this a base class?
        if (item instanceof Function) {

            // this is a base class

            if (ODebug) {
                if (klass.baseclass) {
                    OError("OClass", "Classes can have only 1 base class.");
                }
            }

            // set the baseclass
            klass.baseclass = item;

            // extend the prototype
            OExtend(klass.prototype, item.prototype);


            // do we need to remember this 
            if (item.prototype.OClass_onClassReady instanceof Function) {

                onClassReadyDelegates = onClassReadyDelegates || [];
                onClassReadyDelegates.push(item.prototype.OClass_onClassReady.bind(item));

            }


        } else {


            if (item.OClass_shouldMixin !== false) {

                // extend the prototype with properties from this item
                OExtend(klass.prototype, item);

            }

            // do we need to remember this 
            if (item.OClass_onClassReady instanceof Function) {

                onClassReadyDelegates = onClassReadyDelegates || [];
                onClassReadyDelegates.push(item.OClass_onClassReady.bind(item));

            }

        }

    }

    // create "base_" accessors where needed
    if (klass.baseclass) {


        // add the "base_" methods to the klass
        for (property in klass.baseclass.prototype) {

            // has this method been overridden?
            if (definition[property]) {

                // create accessor
                klass.prototype[OClass.basePrefix + property] = klass.baseclass.prototype[property];

            }

        }


    }

    // extend the class with the definition
    OExtend(klass.prototype, definition);

    // look for special values
    if (OProvided("objx.event") || OProvided("objx.property")) {

        for (property in klass.prototype) {


            if (OProvided("objx.event") && klass.prototype[property] === OEvent) {

                // add the events

                klass.prototype[property] = (function(property) {

                    return function() {


                        // setup the event (once!) - this method will be overwritten with the property event method
                        OEvent(this, property);


                        // pass on the call to the propert event method
                        this[property].apply(this, arguments);


                        // allow chaining...
                        return this;


                    };
                })(property);

            } else if (OProvided("objx.property") && klass.prototype[property] === OProperty) {

                OProperty(klass.prototype, property);

            }

        }

    }

    // set the constructor and kind of the instnace
    klass.prototype.constructor = klass.prototype.kind = klass;

    // this class is a 'kind' of OClass
    klass.kind = OClass;

    // add the fromPojo function
    klass.fromPojo = OClass.fromPojo.bind(klass);

    // are there any onClassReadyDelegates to call?
    if (onClassReadyDelegates !== null) {
        objx(onClassReadyDelegates).each(function( delegate ){
            delegate(klass.prototype, definition);
        });
    };

    // return the class
    return klass;


};


// The prefix for base classes
OClass.basePrefix = "base_";


// Static method to set class properties from a POJO
OClass.fromPojo = function(pojo){

    var instance = new this();

    objx(pojo).each(function(value, key){
        instance["_" + key] = value;
    });

    return instance;

};


// comparer to check if an instance is of a given OClass type
OCheck.comparers.isOClassType = function(obj, type) {

    if (obj && obj.kind && obj.kind === type) {
        return true;
    }
    return false;

};


OProvides("objx.class");