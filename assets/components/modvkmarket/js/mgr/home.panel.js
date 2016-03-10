modVkMarket.panel.Home = function (config) {
	config = config || {};
	Ext.apply(config, {
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		style: {margin: '0 25px 0 25px'},
		hideMode: 'offsets',
		items: [{
			html: '<h2>' + _('modvkmarket') + '</h2>',
			cls: '',
			style: {margin: '15px 0'}
		}, {
			xtype: 'modx-tabs',
			defaults: {border: false, autoHeight: true},
			border: true,
			hideMode: 'offsets',
			stateful: true,
			stateId: 'modvkmarket-panel-home',
			stateEvents: ['tabchange'],
			getState:function() {return { activeTab:this.items.indexOf(this.getActiveTab())};},
			items: [{
				title: _('modvkmarket_goods'),
				layout: 'anchor',
				items: [{
					html: _('modvkmarket_goods_intro_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'modvkmarket-grid-goods',
					cls: 'main-wrapper',
				}]
			},{
				title: _('modvkmarket_categories'),
				layout: 'anchor',
				items: [{
					html: _('modvkmarket_categories_intro_msg'),
					cls: 'panel-desc',
				}, {
					xtype: 'modvkmarket-grid-categories',
					cls: 'main-wrapper',
				}]
			}]
		}]
	});
	modVkMarket.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(modVkMarket.panel.Home, MODx.Panel);
Ext.reg('modvkmarket-panel-home', modVkMarket.panel.Home);
