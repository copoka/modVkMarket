var modVkMarket = function (config) {
	config = config || {};
	modVkMarket.superclass.constructor.call(this, config);
};
Ext.extend(modVkMarket, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('modvkmarket', modVkMarket);

modVkMarket = new modVkMarket();