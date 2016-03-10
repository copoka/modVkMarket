modVkMarket.window.Console  = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        baseParams: {
            action: config.action || 'console'
        }
    });

    Ext.applyIf(config,{
        title: _('console')
        ,modal: Ext.isIE ? false : true
        ,closeAction: 'hide'
        ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: true
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 650
        ,refreshRate: 2
        ,cls: 'modx-window modx-console'
        ,items: [{
            itemId: 'header'
            ,cls: 'modx-console-text'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,itemId: 'body'
            ,cls: 'x-form-text modx-console-text'
            ,border: false
        }]
        ,buttons: [
            {
                text: _('ok')
                ,itemId: 'okBtn'
                ,disabled: false
                ,scope: this
                ,handler: this.close
            }
        ]
        ,keys: [{
            key: Ext.EventObject.S
            ,ctrl: true
            ,fn: this.download
            ,scope: this
        },{
            key: Ext.EventObject.ENTER
            ,fn: this.close
            ,scope: this
        }]

        ,autoHeight: false
        ,url: modVkMarket.config.connector_url
    });

    config.baseParams.output_format = 'json';
    config.baseParams.modvkmarket_in_console_mode = true;   // Для отладки

    modVkMarket.window.Console.superclass.constructor.call(this,config);

    this.on('show', this.startWork);
    // this.on('hide', this.close, this);
};

Ext.extend(modVkMarket.window.Console, MODx.Window,{

    startWork: function(){
        // console.log(this.submit);
        this.submit();
    }

    ,submit: function(close) {
        // console.log(this);
        // return;
        close = close === false ? false : true;
        var f = this.fp.getForm();


        if (f.isValid() && this.fireEvent('beforeSubmit',f.getValues())) {
            f.submit({
                scope: this
                ,failure: function(frm,response) {

                    // console.log(response);
                    var response = Ext.decode(response.response.responseText);

                    // console.log(this);
                    // console.log(response);
                    // if (this.fireEvent('failure',{f:frm,a:a})) {
                    //     MODx.form.Handler.errorExt(a.result,frm);
                    // }
                    // return;

                    response.level = response.level || 1;

                    this.log(response);
                }
                ,success: function(frm, response) {
                    //console.log(this);
                    // console.log(frm);
                    // console.log(response);
                    //
                    // return;


                    try{

                        var response = Ext.decode(response.response.responseText);

                        //console.log(response);

                        var object = response.object;

                        // console.log(response);

                        // this.log(response.result.message);
                        this.log(response);

                        if (!response.continue) {
                            this.fireEvent('complete');
                            //console.log(thisGrid);
                            this.fbar.setDisabled(false);
                            return;
                        }

                        // console.log(response);

                    }
                    catch(e){
                        alert('Ошибка разбора ответа');
                        console.log(e);
                        return;
                    }

                    /*if (this.config.success) {
                     Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
                     }
                     this.fireEvent('success',{f:frm,a:a});*/

                    if(this.isVisible()){
                        //console.log(this);
                        //console.log(this.submit);
                        this.submit();
                    }

                    // if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close(); }
                }
            });
        }
    }

    ,log: function(response){
        try{
            var msg = response.message;
            var level = response.level;
            var cls = '';

            switch(level){
                case 1:
                    cls = 'error';
                    break;

                case 2:
                    cls = 'warn';
                    break;

                case 3:
                    cls = 'info';
                    break;

                case 4:
                    cls = 'debug';
                    break;
            }

            msg = '<p class="'+cls+'">'+msg+'</p>';

            var out = this.getComponent('body');
            if (out) {
                out.el.insertHtml('beforeEnd', msg);
                out.el.scroll('b', out.el.getHeight(), true);
            }
        }
        catch(e){
            alert(e);
            return;
        }
    }

    // ,close: function(){
    //     console.log(this);
    // }
});
