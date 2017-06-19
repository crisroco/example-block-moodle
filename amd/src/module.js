define(['jquery'], function($) {
    function init(){
    $(document).ready(
        function(){
            $("#menugroup").change(function () {
                $("#menugroup option:selected").each(function () {
                    var groupid=$(this).val();
                    var courseid=$("#courseidd").val();
                    $.post("/moodleold/blocks/scorm_report/scormList.php", { groupid: groupid, courseid:courseid }, function(data){
                        $("#menuscorm").html(data);
                    });                   
                });
            });
        }
    );
    }
    return {
        init:init
    };

});

