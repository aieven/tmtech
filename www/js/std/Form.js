define( 'Form', [ 'Form.Field', 'Template' ], function( Field, Template ){

var
    emptyFn = function(){},

    ALERT_TPL =
        '<div class="alert fade in{{1}}"><a class="close" data-dismiss="alert" href="#">&times;</a>{{2}}</div>';

return OClass({
    $form: null,

    _locked: false,
    _fields: null,
    _alerts: null,

    init: function( $form, options ){
        options = options || {};

        var type = $form.attr('data-type'),
            fields = options.fields || {};
        if(!type )
            type = 'json';

        this.$form = $form;
        this.$form.data( 'form', this );
        this._fields = {};
        this._alerts = [];
        for( var i in fields ){
            if( fields.hasOwnProperty( i )){
                this._fields[i] = new Field( this, i, fields[i] );
            }
        }
        this.initAjax( type, options.success || emptyFn, options.error || emptyFn );
    },

    addField: function( name, options ){
        if( this._fields.hasOwnProperty( name ) ){
            this._fields[name].activate();
        }else{
            this._fields[name] = new Field( this, name, options );
        }
    },

    addFields: function( fields ){
        var Form = this;
        for( var i in fields )
            if( fields.hasOwnProperty( i ))
                Form.addField( i, fields[i] );
    },

    activate: function( name ){
        if( this._fields.hasOwnProperty( name ) )
            this._fields[name].activate();
    },

    deactivate: function( name ){
        if( this._fields.hasOwnProperty( name ) )
            this._fields[name].deactivate();
    },

    lock: function(){
        this._locked = true;
        this.$form.find( '[type=submit]' ).attr( 'disabled', true );
    },

    unlock: function(){
        this._locked = false;
        this.$form.find( '[type=submit]' ).attr( 'disabled', false );
    },

    /**
     * @param {string} type
     * @param {Function?} success
     * @param {Function?} error
     */
    initAjax: function( type, success, error ){
        var self = this;
        this.$form.ajaxForm({
            dataType: type,
            beforeSubmit: function(){
                if( self._locked )
                    return false;

                self.lock();

                var result = self.validate();
                if(!result )
                    self.unlock();

                return result;
            },
            complete: function(){
                self.unlock();
            },
            success: function( content ){
                if( 'json' === type ){
                    if( content.error ){
                        self.showAlert( I18n.get( content.error ), 'error' );
                        error && error.call( self, content );
                        return;
                    }
                    else if( content.fields_errors ){
                        self.showFieldsErrors( content.fields_errors );
                        error && error.call( self, content );
                        return;
                    }
                    else if( content.warning ){
                        self.showAlert( I18n.get( content.warning ));
                        error && error.call( self, content );
                        return;
                    }
                    else if( content.success ){
                        self.showAlert( I18n.get( content.success ), 'success' );
                    }
                }
                success && success.call( self, content );
            }
        });
    },

    validate: function(){
        this.clearAlerts();
        var valid = true;
        for( var i in this._fields ){
            if( this._fields.hasOwnProperty( i ))
                valid = this._fields[i].validate() && valid;
        }
        return valid;
    },

    validateField: function( name ){
        if( this._fields.hasOwnProperty( name ))
            return this._fields[name].validate();
        else
            return false;
    },

    clearAlerts: function(){
        var $alert;
        while( $alert = this._alerts.pop())
            $alert.alert('close');
    },

    /**
     * @param {string} text
     * @param {string} type
     */
    showAlert: function( text, type ){
        if( type )
            type = ' alert-'+ type;
        var $errors = this.$form.find('.-form-errors-');
        if(!$errors.length )
            $errors = $('#global-errors');
        var $alert = $( Template.parse( ALERT_TPL, type, text ))
                .appendTo( $errors )
                .hide()
                .slideDown()
                .bind( 'close', function (){
                    $alert.slideUp();
                })
                .bind( 'closed', function (){
                    $alert.remove();
                });
        this._alerts.push( $alert );
    },

    showFieldsErrors: function( errors,/*String?*/prefix ){
        prefix = prefix ? prefix + '-' : '';

        var fieldName;
        for( var i in errors ){
            if( errors.hasOwnProperty( i )){
                fieldName = prefix + i;
                if( errors[i] instanceof Object ){
                    this.showFieldsErrors( errors[i], fieldName )
                }else{
                    if( this._fields.hasOwnProperty( fieldName )){
                        this._fields[fieldName].error( I18n.get( errors[i] ));
                    }else{
                        this.showAlert( I18n.get( errors[i] ), 'error' );
                    }
                }
            }
        }
    }
});
});
