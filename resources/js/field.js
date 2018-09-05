import VueFroala from 'vue-froala-wysiwyg'

Nova.booting((Vue, router) => {
	Vue.use(VueFroala);
    Vue.component('index-wysiwyg-field', require('./components/IndexField'));
    Vue.component('detail-wysiwyg-field', require('./components/DetailField'));
    Vue.component('form-wysiwyg-field', require('./components/FormField'));
})
