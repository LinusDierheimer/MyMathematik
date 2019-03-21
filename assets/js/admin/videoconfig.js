import "../../scss/admin/videoconfig.scss";
import "../app.js";

function initOptions(){

    const $this = $(this);
    const $input = $this.find('.videoname');
    const originValue = $input.val();
    const $renameOptions = $this.find(".nameoptions");
    const $videoOptions = $this.find(".videooptions");

    $input.on('input', function(){
        if($input.val() != originValue)
            $renameOptions.css("display", "inline-block");
        else
            $renameOptions.css("display", "none");
    });

    $renameOptions.find(".discardrename").click(function(){
        $renameOptions.css("display", "none");
        $input.val(originValue);
    });

    $renameOptions.find(".dorename").click(function(){
        $renameOptions.css("display", "none");
        $.ajax("/admin/doaction", {
            data: {
                action: "rename",
                from: $(this).data("file"),
                to: $input.val(),
            },
            success: function(data){
            },
            error: function(error){
                console.error(error);
                $input.val(originValue);
            }
        });
    });

    $videoOptions.find(".delete").click(function(){
        if(!confirm("Do you really want to delete the video? It will be deleted from disk. Consider removing it just from the config file."))
            return;
        
        $.ajax("/admin/doaction", {
            data: {
                action: "delete",
                file: $(this).data("file")
            },
            success: function(data){
                $this.remove();
            },
            error: function(error){
                console.error(error);
            }
        });
    });
}

function initAllOptions(){
    $(".videofile").each(initOptions);
}

function initResetConfig(){
    const $editor = $(".editor");
    const originContent = $editor.val();

    $("#resetConfig").click(function(){
        $editor.val(originContent);
    })
}

$(document).ready(function(){
    initAllOptions();
    initResetConfig();
})