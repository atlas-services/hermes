import {
    ClassicEditor,
    Alignment,
    BlockQuote,
    Bold,
    Essentials,
    Font,
    FontBackgroundColor,
    FontSize,
    FontFamily,
    GeneralHtmlSupport,
    HtmlEmbed,
    Indent, 
    IndentBlock,
    Italic,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    Image,
    List,
    Link,
    MediaEmbed,
    Paragraph,
    SimpleUpload,
    SimpleUploadAdapter,
    SourceEditing,
    Strikethrough,
    Underline,
  } from "ckeditor5";
  
  import 'ckeditor5/dist/ckeditor5.css';
  
// post_content est la valeur issue du formulaire (donc de la base de donnée)
let postContent = document.querySelector('#post_content');
if(null == postContent){
  postContent = document.querySelector('#section_template_posts_0_content');
}
let postContentValue = '';
if(null != postContent){
  postContentValue = postContent.value;
}

// editor est la balise contenant le formattage ckeditor
const ckeditor = document.querySelector("#editor");

if(null != ckeditor){
// on insert les datas de la base dans le formattage ckeditor
ckeditor.innerHTML = postContentValue;

const editConfig =  {
    plugins: [ 
      Alignment,
      BlockQuote,
      Bold, 
      Essentials, 
      Font, 
      FontBackgroundColor, 
      FontSize, 
      FontFamily, 
      GeneralHtmlSupport,
      HtmlEmbed,  
      Indent, 
      IndentBlock,
      Italic,     
      ImageBlock,
      ImageCaption,
      ImageInline,
      ImageInsert,
      ImageInsertViaUrl,
      ImageResize,
      ImageTextAlternative,
      ImageToolbar,
      ImageUpload,
      Image,
      List,
      Link,
      MediaEmbed,
      Paragraph,
      SimpleUploadAdapter, 
      SourceEditing,
      Strikethrough,
      Underline
    ],
    toolbar: {
      items: [
        'sourceEditing',
        "undo",
        "redo",
        "|",
        "list",
        "paragraph",
        "fontSize",
        "fontFamily",
        "fontColor",
        "fontBackgroundColor",
        "|",
        "bold",
        "italic",
        'underline',
        'strikethrough',
        "blockQuote",
        '|', 'alignment:left', 'alignment:center', 'alignment:justify', 'alignment:right',
        "|",
        'bulletedList',
        'numberedList',
        "|",
        'outdent', 
        'indent',
        "|",
        'link',
        'mediaEmbed',
        'insertImage',
      ],
    },
    heading: {
			options: [
				{
					model: 'paragraph',
					title: 'Paragraph',
					class: 'ck-heading_paragraph',
				},
				{
					model: 'heading1',
					view: 'h1',
					title: 'Heading 1',
					class: 'ck-heading_heading1',
				},
				{
					model: 'heading2',
					view: 'h2',
					title: 'Heading 2',
					class: 'ck-heading_heading2',
				},
				{
					model: 'heading3',
					view: 'h3',
					title: 'Heading 3',
					class: 'ck-heading_heading3',
				},
				{
					model: 'heading4',
					view: 'h4',
					title: 'Heading 4',
					class: 'ck-heading_heading4',
				},
			],
		},
    htmlSupport: {
      allow: [
          {
              name: /.*/,
              attributes: true,
              classes: true,
              styles: true
          }
      ]
    },
    simpleUpload :{
      uploadUrl: "/api/file/upload"

    },
    mediaEmbed: {
      previewsInData:true
    },
  };
  ClassicEditor.create(ckeditor, editConfig)
    .then((editor) => {
      editor.sourceElement.parentElement.addEventListener("submit", function (e) {
        e.preventDefault();
        postContent.value = editor.getData();
        this.submit();
      });
    })
    .catch(/* ... */);
}
