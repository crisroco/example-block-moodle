define(['jquery'], function($) {
  function init(){
    $(document).ready(
      /*function(){
        $(function update() {

           $(".mform .btn").click( function (e) {

             e.preventDefault();
             var form = $(this).parent().parent();
             var key = $(this).parent().parent().serializeArray()[3].value;   
             key = key.split("&");
             console.log(key[3]);

             $.ajax({
               type: "post",
               url: "/local/essay_amag/updateGradeQuiz.php",
               data: $(form).serialize(),
               success: function(data) {
                //console.log(data);
                   $("#grade_error").html("");
                 if (data == "grade_error") {
                   $("#grade_error"+key[3]).html("<span class='error' >La calificación se encuentra fuera del rango válido</span>");
                   $("#grade").val("");
                   //$("#grade_error").html(key);

                 }else{
                  $("#grade_error"+key[3]).html("<div class='alert alert-info'><strong>Éxito!</strong> Nota ingresada.</div>");
                 }
               }
             });

           });

       });
      }*/
      $("#menugroup").change(function () {
           $("#menugroup option:selected").each(function () {
            groupid=$(this).val();
            courseid=$("#courseidd").val();
            console.log(groupid);
            console.log(courseid);
            $.post("/blocks/scorm_report/scormList.php", { groupid: groupid, courseid:courseid }, function(data){
            $("#menuscorm").html(data);
            });   

                
        });
      })

    );
  }
  return {
    init:init
  }

});

