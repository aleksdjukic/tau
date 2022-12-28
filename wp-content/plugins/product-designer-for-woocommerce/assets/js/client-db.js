/* global indexedDB, IDBDatabase */

( function( $ ) {
    var db, _options = { }, _request = { };

    /**
     * Call to Client DB class.
     * 
     * @param string name
     * @param int version
     * @param array|object stores
     * @returns {dbL#3.iDB|iDB|Boolean}
     */
    PDR_ClientDB = function( name, version, stores = {} ) {
        // Check for IndexedDB support
        if ( ! PDR_ClientDB.isSupported ) {
            console.log( "Your browser doesn't support IndexedDB" );
            return false;
        }

        return ( new iDB( name, version, stores ) );
    };

    PDR_ClientDB.isSupported = window.indexedDB || window.webkitIndexedDB || window.mozIndexedDB || window.OIndexedDB || window.msIndexedDB;

    /**
     * Handles the PDR database via indexedDB.
     * 
     * @param string name
     * @param int version
     * @param array|object stores
     * @returns {Boolean}
     */
    var iDB = function( name, version, stores ) {
        if ( '' === name ) {
            console.log( "Database name is required" );
            return false;
        }

        if ( isNaN( version ) || ! $.isNumeric( version ) ) {
            console.log( "Database version is invalid" );
            return false;
        }

        // Prepare object stores
        this.parseStores( stores );

        // Connect DB
        _request.open = indexedDB.open( name, version );

        // DB handlers  
        _request.open.onsuccess = this.onReady;
        _request.open.onerror = this.onError;
        _request.open.onupgradeneeded = this.onUpgrade;
    };

    /**
     * Get default object store.
     *
     * @type {object}
     */
    iDB.prototype.getDefaultStore = {
        name : '', // store name which is similar to table name
        keyOptions : {
            keyPath : 'id', // a path to an object property that IndexedDB will use as the key, e.g. id
            autoIncrement : true // if true, then the key for a newly stored object is generated automatically, as an ever-incrementing number.
        }
    };

    /**
     * Parse object stores.
     * 
     * @param array|object stores
     * @returns {Boolean}
     */
    iDB.prototype.parseStores = function( stores ) {
        if ( 'object' !== typeof stores ) {
            return false;
        }

        _options.stores = [ ];

        if ( stores.hasOwnProperty( 'name' ) ) {
            _options.stores[0] = $.extend( { }, this.getDefaultStore, stores );
        } else {
            for ( let i in stores ) {
                _options.stores[i] = $.extend( { }, this.getDefaultStore, stores[i] );
            }
        }
    };

    /**
     * DB is ready.
     */
    iDB.prototype.onReady = function( e ) {
        let result = e.target.result;

        if ( result instanceof IDBDatabase ) {
            db = result;

            $( document ).trigger( 'pdr_clientdb_ready_handler' );
        }

        console.log( "Database connected successful", result );
    };

    /**
     * DB Error.
     */
    iDB.prototype.onError = function( e ) {
        console.log( "Database connection unsuccessful" );
    };

    /**
     * Database is ready but the version is outdated.
     * 
     * An object store can only be created/modified while updating the DB version, in _request.open.onupgradeneeded handler.
     */
    iDB.prototype.onUpgrade = function( e ) {
        db = e.target.result;

        for ( let i in _options.stores ) {
            // create the object store 
            db.createObjectStore( _options.stores[i].name, _options.stores[i].keyOptions );
        }

        console.log( "Database upgraded successful" );
    };

    /**
     * When transaction completed with success.
     */
    iDB.prototype.onTxnComplete = function( e ) {
        if ( db instanceof IDBDatabase ) {
            db.close();
        }

        console.log( "Transaction is complete" );
    };

    /**
     * When transaction is aborted.
     */
    iDB.prototype.onTxnAbort = function( e ) {
        console.log( "Transaction is aborted" );
    };

    /**
     * Create the transaction.
     * 
     * @param string store. A store name
     * @param string type. Transaction type, either readonly or readwrite
     * @param string method
     * @param mixed value
     * @param string|null key
     * @returns {Boolean}
     */
    iDB.prototype.createTxn = function( store, type = 'readonly', method = 'get', value = null, key = null ) {
        if ( ! db instanceof IDBDatabase ) {
            return false;
        }

        let txn = db.transaction( store, type );

        // Create a new transaction
        if ( 'get' === method || 'delete' === method ) {
            _request.createTxn = txn.objectStore( store )[method]( key );
        } else if ( 'getAll' === method ) {
            _request.createTxn = txn.objectStore( store );
        } else if ( 'deleteAll' === method ) {
            _request.createTxn = txn.objectStore( store ).clear();
        } else {
            if ( null === key ) {
                _request.createTxn = txn.objectStore( store )[method]( value );
            } else {
                _request.createTxn = txn.objectStore( store )[method]( value, key );
            }

            // Transaction handler
            txn.oncomplete = this.onTxnComplete;
        }

        return _request.createTxn;
    };

    /**
     * To get the value by key from the object store.
     * 
     * @param string store
     * @param string key
     */
    iDB.prototype.getValue = function( store, key ) {
        return ( db instanceof IDBDatabase ) ? this.createTxn( store, 'readonly', 'get', null, key ) : { };
    };

    /**
     * To get all values from the object store.
     * 
     * @param string store
     */
    iDB.prototype.getValues = function( store ) {
        return ( db instanceof IDBDatabase ) ? this.createTxn( store, 'readonly', 'getAll' ) : { };
    };

    /**
     * To add value in to the object store.
     * 
     * The key is supplied only if the object store did not have keyPath or autoIncrement option. 
     * If there’s already a value with the same key, then the request fails, and an error with the name "ConstraintError" is generated.
     * 
     * @param string store
     * @param mixed value
     * @param string|null key
     */
    iDB.prototype.addValue = function( store, value, key = null ) {
        return ( db instanceof IDBDatabase ) ? this.createTxn( store, 'readwrite', 'add', value, key ) : { };
    };

    /**
     * To update value in to the object store.
     * 
     * The key is supplied only if the object store did not have keyPath or autoIncrement option. 
     * If there’s already a value with the same key, it will be replaced.
     * 
     * @param string store
     * @param mixed value
     * @param string key
     */
    iDB.prototype.updateValue = function( store, value, key = null ) {
        return ( db instanceof IDBDatabase ) ? this.createTxn( store, 'readwrite', 'put', value, key ) : { };
    };

    /**
     * To delete value from the object store.
     * 
     * @param string store
     * @param string key
     */
    iDB.prototype.deleteValue = function( store, key ) {
        return ( db instanceof IDBDatabase ) ? this.createTxn( store, 'readwrite', 'delete', null, key ) : { };
    };

    /**
     * To delete all values from the object store.
     * 
     * @param string store
     */
    iDB.prototype.deleteValues = function( store ) {
        return ( db instanceof IDBDatabase ) ? this.createTxn( store, 'readwrite', 'deleteAll' ) : { };
    };
} )( jQuery );