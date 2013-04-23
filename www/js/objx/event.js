/*
 *  OEvent(obj, eventName)
 *  objx(o).event("onSomething")
 *  Adds events to an object
 */
objx.fn.event = function(name) {

    // create the event
    OEvent(this.obj(), name);

    // return this for chaining
    return this;

};




OEvent = function(obj, eventName) {


    if (ODebug) {


        if (!obj || obj == null || typeof obj == "undefined") {
            OError("objx.event", "Cannot add events to objects of type " + objx(obj).kind);
        }


        if (!eventName || eventName == "" || typeof eventName == "undefined") {
            OError("objx.event", "You must provide an event name.")
        }


    }


    // add the events structure to the object
    obj.oevent_listeners = obj.oevent_listeners || {};
    obj.oevent_listeners[eventName] = [];


    // add the lovely shortcut method
    obj[eventName] = (function(eName){

        // bind these
        var __eventName = eName;


        // return a function that will call the right event method
        return function(){

            // is the only argument a function?
            if (arguments.length === 1 && arguments[0] instanceof Function) {


                // add the listener
                this["listenFor_" + __eventName].apply(this, arguments);


            } else {


                // fire the event
                this["fire_" + __eventName].apply(this, arguments);


            }


            return this;

        };


    })(eventName);

    // add the forget method
    obj[eventName].forget = OBind(function(eName){

        if (arguments.length === 1) {

            // .forget() - Forget everything
            this.oevent_listeners[eventName] = [];

        } else {
            if (arguments[1] instanceof Function) {

                var cForget = arguments[1];

                // .forget( callback ) - Forget this callback
                objx(this.oevent_listeners[eventName]).each(OBind(function(c, i){
                    if (c === cForget) {

                        // forget this
                        this.oevent_listeners[eventName].splice(i, 1);

                        // stop looking
                        return false;
                    }
                }, this));

            } else {

                // .forget( index ) - Forget this index
                this.oevent_listeners[eventName].splice(arguments[1], 1);

            }
        }

    }, obj, eventName);


    // add the fire item
    obj["fire_" + eventName] = OBind(function() {


        // trigger each listener in the list
        objx(this.oevent_listeners[eventName]).each(


            // bind the right context and arguments
            OBind(


                // the function to bind to
                function(args, item){
                    return item.apply(this, args);
                },


                // 'this' is the object that is firing the event
                this,


                // these are any arguments specified
                arguments


            )
        );


    }, obj);


    // add the listenFor item
    obj["listenFor_" + eventName] = OBind(function(func) {


        // add the listener to the list
        this.oevent_listeners[eventName].push(func);


    }, obj);


    // return this for chaining
    return this;


};


OProvides("objx.event");