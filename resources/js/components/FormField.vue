<template>
    <default-field :field="field">
        <template slot="field">
            <froala
              :id="field.name"
              :tag="'textarea'"
              :config="options"
              :placeholder="field.name"
              v-model="value"
            />
            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors } from 'laravel-nova'

    // Require Froala Editor js file.
    require('froala-editor/js/froala_editor.pkgd.min')

    // Require Froala Editor css files.
    require('froala-editor/css/froala_editor.pkgd.min.css')
    require('froala-editor/css/froala_style.min.css')

    export default {
        mixins: [
        	FormField,
            HandlesValidationErrors
        ],
        props: [
        	'resourceName',
            'resourceId',
            'field'
        ],
		data: () => ({
			options: {}
		}),
        created() {
            this.options = {
				// Char Counter
				'charCounterCount': false,
				// File
				'fileMaxSize': 1024 * 1024 * 25,
				'fileUploadParams': {
					'_token': $('meta[name="csrf-token"]').attr('content'),
					'field': this.field.attribute,
					'resource': this.resourceName,
					'draftId': this.field.draftId,
				},
				'fileUploadURL': '/nova-vendor/michielkempen/nova-wysiwyg-field/wysiwyg-files/store',
				// General
				'editorClass': 'nova-wysiwyg-field',
				'heightMin': 300,
				'pastePlain': true,
				'placeholderText': '',
				'shortcutsEnabled': [
					'bold',
					'italic',
					'underline',
					'undo',
					'redo',
				],
				'shortcutsHint': false,
				'spellcheck': false,
				'tabSpaces': 4,
				'toolbarButtons': [
					'bold', 'italic', 'underline', '|',
					'paragraphFormat', 'quote', '|',
					'formatOL', 'formatUL', '|',
					'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|',
					'html',
				],
				'toolbarButtonsSM': [
					'bold', 'italic', 'underline', '|',
					'paragraphFormat', 'quote', '|',
					'formatOL', 'formatUL'
				],
				'toolbarButtonsXS': [
					'bold', 'italic', 'underline', '|',
					'paragraphFormat', 'quote', '|',
					'formatOL', 'formatUL'
				],
				'toolbarSticky': false,
				'tooltips': false,
				// Image
				'imageDefaultWidth': 500,
				'imageEditButtons': [
					'imageCaption', 'imageSize', 'imageRemove'
				],
				'imageInsertButtons': [
					'imageBack', '|',
					'imageUpload'
				],
				'imageMaxSize': 1024 * 1024 * 25,
				'imagePaste': false,
				'imageOutputSize': true,
				'imageUploadParams': {
					'_token': $('meta[name="csrf-token"]').attr('content'),
					'field': this.field.attribute,
					'resource': this.resourceName,
					'draftId': this.field.draftId,
				},
				'imageUploadURL': '/nova-vendor/michielkempen/nova-wysiwyg-field/wysiwyg-files/store',
				// Link
				'linkAlwaysBlank': true,
				'linkEditButtons': [
					'linkOpen', 'linkEdit', 'linkRemove'
				],
				'linkInsertButtons': [
					'linkBack'
				],
				// Paragraph Format
				'paragraphFormat': {
					'H1': 'Heading',
					'H2': 'Subheading',
					'N': 'Normal',
					'PRE': 'Code'
				},
				// Quick Insert
				'quickInsertTags': [],
				// Table
				'tableEditButtons': [
					'tableHeader', 'tableCells', 'tableRows', 'tableColumns', '|',
					'tableRemove'
				],
				'tableInsertHelper': false,
				// Video
				'videoAllowedProviders': [
					'youtube', 'vimeo'
				],
				'videoDefaultWidth': 500,
				'videoEditButtons': [
					'videoSize', 'videoRemove'
				],
				'videoInsertButtons': [
					'videoBack', '|',
					'videoByURL',
				],
				// Word
				'wordAllowedStyleProps': [],
				'wordPasteModal': false,
                // Events
				'events': {
					'froalaEditor.image.removed': (e, editor, img)  =>{
						Nova.request().post('/nova-vendor/michielkempen/nova-wysiwyg-field/wysiwyg-files/delete', {
                            'fileUrl': $(img).attr('src'),
                            'field': this.field.attribute,
					        'resource': this.resourceName,
                        })
					},
					'froalaEditor.file.unlink': (e, editor, link) => {
						Nova.request().post('/nova-vendor/michielkempen/nova-wysiwyg-field/wysiwyg-files/delete', {
                            'fileUrl': $(link).attr('src'),
                            'field': this.field.attribute,
					        'resource': this.resourceName,
						});
					},
				}
			}
        },
        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
            	this.value = this.field.value || ''
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
            	formData.append(this.field.attribute, this.value || '')
				formData.append(this.field.attribute + 'DraftId', this.field.draftId)
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
            	this.value = value
            }
        }
    }
</script>