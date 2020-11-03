$('img.lazy').Lazy({
        // effect
        effect: 'fadeIn',
        effectTime: 10 ,
        combined: true,
        // callback
        beforeLoad: function(element) {
            console.log("start loading " + element.prop("id"));
        },
    }
);
