modVkMarket.grid.Categories = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'modvkmarket-grid-categories';
	}
	Ext.applyIf(config, {
		url: modVkMarket.config.connector_url,
		save_action: 'mgr/categories/updatefromgrid',
		autosave: true,
		fields: ['id', 'vkgroup_id', 'cat_ids', 'resource_ids'],
		columns: this.getColumns(config),
		tbar: [{
				text: '<i class="icon icon-plus"></i>&nbsp;' + _('modvkmarket_categories_create')
				,handler: this.createItem
				,scope: this
			}
		],
		baseParams: {
			action: 'mgr/categories/getlist'
		},
		paging: true,
		remoteSort: true,
		autoHeight: true,
	});
	modVkMarket.grid.Categories.superclass.constructor.call(this, config);
};
Ext.extend(modVkMarket.grid.Categories, MODx.grid.Grid, {
	windows: {},
	getMenu: function() {
		var m = [];
		m.push({
			text: _('modvkmarket_categories_update'),
			handler: this.updateItem
		});
		m.push('-');
		m.push({
			text: _('modvkmarket_categories_remove'),
			handler: this.removeItem
		});
		this.addContextMenuItem(m);
	},
	getColumns: function (config) {
		var columns = [];
		var add = {
			id: { hidden: true, width: 50, sortable: false },
			vkgroup_id: { width: 80, sortable: true, editor: {xtype:'numberfield'} },
			cat_ids: { width: 200, sortable: true, editor: {xtype:'textfield'} },
			resource_ids: { width: 200, sortable: true, editor: {xtype:'textfield'} }
		};
		for (var field in add) {
			if (add[field]) {
				Ext.applyIf(add[field], {
					header: _('modvkmarket_categories_' + field),
					dataIndex: field
				});
				columns.push(add[field]);
			}
		}
		return columns;
	},
	createItem: function(btn,e) {
		if (!this.windows.createItem) {
			this.windows.createItem = MODx.load({
				xtype: 'modvkmarket-window-category-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.createItem.fp.getForm().reset();
		this.windows.createItem.show(e.target);
	},
	updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;
		MODx.Ajax.request({
			url: modVkMarket.config.connector_url
			,params: {
				action: 'mgr/categories/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					if (!this.windows.updateItem) {
						this.windows.updateItem = MODx.load({
							xtype: 'modvkmarket-window-category-update'
							,record: r
							,listeners: {
								'success': {fn:function() { this.refresh(); },scope:this}
							}
						});
					}
					this.windows.updateItem.fp.getForm().reset();
					this.windows.updateItem.fp.getForm().setValues(r.object);
					this.windows.updateItem.show(e.target);
				},scope:this}
			}
		});
	},
	removeItem: function(btn,e) {
		if (!this.menu.record) return;
		MODx.msg.confirm({
			title: _('modvkmarket_categories_remove'),
			text: _('modvkmarket_categories_remove_confirm'),
			url: modVkMarket.config.connector_url,
			params: {
				action: 'mgr/categories/remove'
				,ids: this.menu.record.id
			},
			listeners: {
				success: {fn:function(r) { this.refresh();}, scope:this}
			}
		});
	}
});
Ext.reg('modvkmarket-grid-categories', modVkMarket.grid.Categories);

modVkMarket.window.CreateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('modvkmarket_categories_create')
		,id: this.ident
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,url: modVkMarket.config.connector_url
		,action: 'mgr/categories/create'
		,fields: [
			{xtype: 'textfield',fieldLabel: _('modvkmarket_categories_vkgroup_id'),name: 'vkgroup_id',id: 'mvm-'+this.ident+'-vkgroup_id',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('modvkmarket_categories_cat_ids'),name: 'cat_ids',id: 'mvm-'+this.ident+'-cat_ids',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('modvkmarket_categories_resource_ids'),name: 'resource_ids',id: 'mvm-'+this.ident+'-resource_ids',anchor: '99%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	modVkMarket.window.CreateItem.superclass.constructor.call(this,config);
};
Ext.extend(modVkMarket.window.CreateItem,MODx.Window);
Ext.reg('modvkmarket-window-category-create',modVkMarket.window.CreateItem);

modVkMarket.window.UpdateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'meuitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('modvkmarket_categories_update')
		,id: this.ident
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,url: modVkMarket.config.connector_url
		,action: 'mgr/categories/update'
		,fields: [
			{xtype: 'hidden',name: 'id',id: 'mvm-'+this.ident+'-id'}
			,{xtype: 'textfield',fieldLabel: _('modvkmarket_categories_vkgroup_id'),name: 'vkgroup_id',id: 'mvm-'+this.ident+'-vkgroup_id',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('modvkmarket_categories_cat_ids'),name: 'cat_ids',id: 'mvm-'+this.ident+'-cat_ids',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('modvkmarket_categories_resource_ids'),name: 'resource_ids',id: 'mvm-'+this.ident+'-resource_ids',anchor: '99%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	modVkMarket.window.UpdateItem.superclass.constructor.call(this,config);
};
Ext.extend(modVkMarket.window.UpdateItem,MODx.Window);
Ext.reg('modvkmarket-window-category-update',modVkMarket.window.UpdateItem);