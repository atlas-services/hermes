import {
    ClassicEditor,
    Bold,
    Essentials,
    Font,
    FontBackgroundColor,
    FontSize,
    FontFamily,
    GeneralHtmlSupport,
    HtmlEmbed,
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
    Link,
    Paragraph,
    SimpleUpload,
    SimpleUploadAdapter,
    SourceEditing,
    Strikethrough,
  } from "ckeditor5";
  
  import 'ckeditor5/dist/ckeditor5.css';
  
// post_content est la valeur issue du formulaire (donc de la base de donnée)
const postContent = document.querySelector('#post_content');
const postContentValue =postContent.value;
// editor est la balise contenant le formattage ckeditor
const ckeditor = document.querySelector("#editor");
// on insert les datas de la base dans le formattage ckeditor
ckeditor.innerHTML = postContentValue;

const editConfig =  {
    plugins: [ 
      Bold, 
      Essentials, 
      Font, 
      FontBackgroundColor, 
      FontSize, 
      FontFamily, 
      GeneralHtmlSupport,
      HtmlEmbed,  
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
      Link,
      Paragraph,
      SimpleUploadAdapter, 
      SourceEditing,
      Strikethrough
    ],
    toolbar: {
      items: [
        'sourceEditing',
        "undo",
        "redo",
        "|",
        "bold",
        "italic",
        'underline',
        'strikethrough',
        "|",
        "fontSize",
        "fontFamily",
        "fontColor",
        "fontBackgroundColor",
        'link',
        'insertImage'
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

    }
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
  