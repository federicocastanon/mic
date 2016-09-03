  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <link rel=stylesheet href="<?php echo assets_url("gantt/css/platform.css"); ?>" type="text/css">
  <link rel=stylesheet href="<?php echo assets_url("gantt/libs/dateField/jquery.dateField.css")?>" type="text/css">
  <link rel=stylesheet href="<?php echo assets_url("gantt/css/gantt.css")?>" type="text/css">
  <link rel=stylesheet href="<?php echo assets_url("gantt/css/print.css")?>" type="text/css" media="print">
  <script src="<?php echo assets_url("gantt/libs/jquery.livequery.min.js")?>"></script>
  <script src="<?php echo assets_url("gantt/libs/jquery.timers.js")?>"></script>
  <script src="<?php echo assets_url("gantt/libs/platform.js")?>" ></script>
  <script src="<?php echo assets_url("gantt/libs/date.js")?>"></script>
  <script src="<?php echo assets_url("gantt/libs/i18nJs.js")?>"></script>
  <script src="<?php echo assets_url("gantt/libs/dateField/jquery.dateField.js")?>"></script>
  <script src="<?php echo assets_url("gantt/libs/JST/jquery.JST.js")?>"></script>
  <script src="<?php echo assets_url("gantt/js/ganttUtilities.js")?>"></script>
  <script src="<?php echo assets_url("gantt/js/ganttTask.js")?>"></script>
  <script src="<?php echo assets_url("gantt/js/ganttDrawer.js")?>"></script>
  <script src="<?php echo assets_url("gantt/js/ganttGridEditor.js")?>"></script>
  <script src="<?php echo assets_url("gantt/js/ganttMaster.js")?>"></script>




<div id="workSpace" style="padding:0px; overflow-y:auto; overflow-x:hidden;border:1px solid #e5e5e5;position:relative;margin:0 5px"></div>

<div id="taZone" style="display:none;" class="noprint">
  <textarea rows="8" cols="150" id="ta">
     {"tasks":[
    <?php echo $rdo;?>
    ],"selectedRow":0,"deletedTaskIds":[],"canWrite":true,"canWriteOnParent":true }
  </textarea>
  <button onclick="loadGanttFromServer();">load</button>
</div>

<style>
  .resEdit {
    padding: 15px;
  }

  .resLine {
    width: 95%;
    padding: 3px;
    margin: 5px;
    border: 1px solid #d0d0d0;
  }

  body {
    overflow: hidden;
  }
</style>

<form id="gimmeBack" style="display:none;" action="../gimmeBack.jsp" method="post" target="_blank"><input type="hidden" name="prj" id="gimBaPrj"></form>

<script type="text/javascript">

var ge;  //this is the hugly but very friendly global var for the gantt editor
$(function() {

  //load templates
  $("#ganttemplates").loadTemplates(); 
  
	
  // here starts gantt initialization
  ge = new GanttMaster();
  var workSpace = $("#workSpace");
  workSpace.css({width:$(window).width() - 20,height:$(window).height() - 100});
  ge.init(workSpace);

  //inject some buttons (for this demo only)
  $(".ganttButtonBar div").append("<button onclick='clearGantt();' class='button'>Limpiar</button>")
          .append("<button onclick='window.print();' class='button'>Imprimir</button>")
          .append("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
          //.append("<button onclick='getFile();' class='button'></button>");
  $(".ganttButtonBar h1").html("");
  $(".ganttButtonBar div").addClass('buttons');
  //overwrite with localized ones
  loadI18n();

  //simulate a data load from a server.
  loadGanttFromServer();


  //fill default Teamwork roles if any
  if (!ge.roles || ge.roles.length == 0) {
    setRoles();
  }

  //fill default Resources roles if any
  if (!ge.resources || ge.resources.length == 0) {
    setResource();
  }


  /*/debug time scale
  $(".splitBox2").mousemove(function(e){
    var x=e.clientX-$(this).offset().left;
    var mill=Math.round(x/(ge.gantt.fx) + ge.gantt.startMillis)
    $("#ndo").html(x+" "+new Date(mill))
  });*/

});


function loadGanttFromServer(taskId, callback) {

  //this is a simulation: load data from the local storage if you have already played with the demo or a textarea with starting demo data
  loadFromLocalStorage();

  //this is the real implementation
  /*
  //var taskId = $("#taskSelector").val();
  var prof = new Profiler("loadServerSide");
  prof.reset();

  $.getJSON("ganttAjaxController.jsp", {CM:"LOADPROJECT",taskId:taskId}, function(response) {
    //console.debug(response);
    if (response.ok) {
      prof.stop();

      ge.loadProject(response.project);
      ge.checkpoint(); //empty the undo stack

      if (typeof(callback)=="function") {
        callback(response);
      }
    } else {
      jsonErrorHandling(response);
    }
  });
  */
}


function saveGanttOnServer() {

  //this is a simulation: save data to the local storage or to the textarea
  
  saveInLocalStorage();


  /*
  var prj = ge.saveProject();

  delete prj.resources;
  delete prj.roles;

  var prof = new Profiler("saveServerSide");
  prof.reset();

  if (ge.deletedTaskIds.length>0) {
    if (!confirm("TASK_THAT_WILL_BE_REMOVED\n"+ge.deletedTaskIds.length)) {
      return;
    }
  }

  $.ajax("ganttAjaxController.jsp", {
    dataType:"json",
    data: {CM:"SVPROJECT",prj:JSON.stringify(prj)},
    type:"POST",

    success: function(response) {
      if (response.ok) {
        prof.stop();
        if (response.project) {
          ge.loadProject(response.project); //must reload as "tmp_" ids are now the good ones
        } else {
          ge.reset();
        }
      } else {
        var errMsg="Errors saving project\n";
        if (response.message) {
          errMsg=errMsg+response.message+"\n";
        }

        if (response.errorMessages.length) {
          errMsg += response.errorMessages.join("\n");
        }

        alert(errMsg);
      }
    }

  });
  */
}


//-------------------------------------------  Create some demo data ------------------------------------------------------
function setRoles() {
  ge.roles = [
    {
      id:"",
      name:""
    }
  ];
}

function setResource() {
  var res = [];
  /*for (var i = 1; i <= 10; i++) {
    res.push({id:"tmp_" + i,name:"Resource " + i});
  }*/
  ge.resources = res;
}


function clearGantt() {
  ge.reset();
}

function loadI18n() {
  GanttMaster.messages = {
    "CHANGE_OUT_OF_SCOPE":"No se puede modificar la tarea padre",
    "START_IS_MILESTONE":"Start/Comienzo es un Hito",
    "END_IS_MILESTONE":"End/Fin es un Hito",
    "TASK_HAS_CONSTRAINTS":"La Tarea tiene restricciones",
    "GANTT_ERROR_DEPENDS_ON_OPEN_TASK":"Error: Depende de una tarea abierta",
    "GANTT_ERROR_DESCENDANT_OF_CLOSED_TASK":"Error: Desciende de una tarea cerrada",
    "TASK_HAS_EXTERNAL_DEPS":"Error: la tarea tiene dependencias externas",
    "GANTT_ERROR_LOADING_DATA_TASK_REMOVED":"Error: Cargando datos eliminados",
    "ERROR_SETTING_DATES":"Error en las fechas",
    "CIRCULAR_REFERENCE":"Referencia Circular",
    "CANNOT_DEPENDS_ON_ANCESTORS":"No puede depender de su antecesor",
    "CANNOT_DEPENDS_ON_DESCENDANTS":"No puede depender de su sucesor",
    "INVALID_DATE_FORMAT":"Fecha Invalida",
    "TASK_MOVE_INCONSISTENT_LEVEL":"Tarea movida a nivel erroneo",

    "GANTT_QUARTER_SHORT":"trim.",
    "GANTT_SEMESTER_SHORT":"sem."
  };
}


//-------------------------------------------  Open a black popup for managing resources. This is only an axample of implementation (usually resources come from server) ------------------------------------------------------
function openResourceEditor() {
  var editor = $("<div>");
  editor.append("<h2>Resource editor</h2>");
  editor.addClass("resEdit");

  for (var i in ge.resources) {
    var res = ge.resources[i];
    var inp = $("<input type='text'>").attr("pos", i).addClass("resLine").val(res.name);
    editor.append(inp).append("<br>");
  }

  var sv = $("<div>save</div>").css("float", "right").addClass("button").click(function() {
    $(this).closest(".resEdit").find("input").each(function() {
      var el = $(this);
      var pos = el.attr("pos");
      ge.resources[pos].name = el.val();
    });
    ge.editor.redraw();
    closeBlackPopup();
  });
  editor.append(sv);

  var ndo = createBlackPage(800, 500).append(editor);
}

//-------------------------------------------  Get project file as JSON (used for migrate project from gantt to Teamwork) ------------------------------------------------------
function getFile() {
  $("#gimBaPrj").val(JSON.stringify(ge.saveProject()));
  $("#gimmeBack").submit();
  $("#gimBaPrj").val("");

  /*  var uriContent = "data:text/html;charset=utf-8," + encodeURIComponent(JSON.stringify(prj));
   neww=window.open(uriContent,"dl");*/
}


//-------------------------------------------  LOCAL STORAGE MANAGEMENT (for this demo only) ------------------------------------------------------
Storage.prototype.setObject = function(key, value) {
  	
  this.setItem(key, JSON.stringify(value));
  var datos = JSON.stringify(value);
  datos_guardar  = datos;
  Guardar(datos);
	
};

function Guardar(datos){
	
	$.ajax({
		type:"POST",
		url:"<?php echo base_url('proyectos/GuardarGantt') ?>",
		data:{json:datos,id:<?php echo $id ?>},
		success:function(respuesta){
			alert(respuesta.men);
		}
	
	});
}


Storage.prototype.getObject = function(key) {
  return this.getItem(key) && JSON.parse(this.getItem(key));
};


function loadFromLocalStorage() {
  var ret;
  /*if (localStorage) {erica
    if (localStorage.getObject("teamworkGantDemo")) {
      ret = localStorage.getObject("teamworkGantDemo");
    }
  } else {
    $("#taZone").show();
  }*/
  if (!ret || !ret.tasks || ret.tasks.length == 0){
    ret = JSON.parse($("#ta").val());


    //actualiza data
    ret = JSON.parse($("#ta").val());
    
    if(ret.tasks[0]){
    	var offset=new Date().getTime()-ret.tasks[0].start;
    }
    
    for (var i=0;i<ret.tasks.length;i++)
      ret.tasks[i].start=ret.tasks[i].start+offset;


  }
  ge.loadProject(ret);
  ge.checkpoint(); //empty the undo stack
}


function saveInLocalStorage() {
  var prj = ge.saveProject();

  if (localStorage) {
    localStorage.setObject("teamworkGantDemo", prj);
  } else {
    $("#ta").val(JSON.stringify(prj));
    alert($("#ta").val());
  }
}


</script>


<div id="gantEditorTemplates" style="display:none;">
  <div class="__template__" type="GANTBUTTONS">
  <div class="ganttButtonBar noprint">
    <h1 style="float:left">task tree/gantt</h1>
    <div class="buttons">
    <button onclick="$('#workSpace').trigger('undo.gantt');" class="button textual" title="Deshacer"><span class="teamworkIcon">&#39;</span></button>
    <button onclick="$('#workSpace').trigger('redo.gantt');" class="button textual" title="Rehacer"><span class="teamworkIcon">&middot;</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('addAboveCurrentTask.gantt');" class="button textual" title="Insertar Arriba"><span class="teamworkIcon">l</span></button>
    <button onclick="$('#workSpace').trigger('addBelowCurrentTask.gantt');" class="button textual" title="Insertar Abajo"><span class="teamworkIcon">X</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('indentCurrentTask.gantt');" class="button textual" title="Correr a la Derecha"><span class="teamworkIcon">.</span></button>
    <button onclick="$('#workSpace').trigger('outdentCurrentTask.gantt');" class="button textual" title="Correr a la izquierda"><span class="teamworkIcon">:</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('moveUpCurrentTask.gantt');" class="button textual" title="Subir"><span class="teamworkIcon">k</span></button>
    <button onclick="$('#workSpace').trigger('moveDownCurrentTask.gantt');" class="button textual" title="Bajar"><span class="teamworkIcon">j</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('zoomMinus.gantt');" class="button textual" title="Achicar"><span class="teamworkIcon">)</span></button>
    <button onclick="$('#workSpace').trigger('zoomPlus.gantt');" class="button textual" title="Agrandar"><span class="teamworkIcon">(</span></button>
    <span class="ganttButtonSeparator"></span>
    <button onclick="$('#workSpace').trigger('deleteCurrentTask.gantt');" class="button textual" title="Borrar"><span class="teamworkIcon">&cent;</span></button>
      &nbsp; &nbsp; &nbsp; &nbsp;
      <button onclick="saveGanttOnServer();" class="button first big" title="Guardar">Guardar</button>
    </div></div>
  </div>

  <div class="__template__" type="TASKSEDITHEAD"><!--
  <table class="gdfTable" cellspacing="0" cellpadding="0">
    <thead>
    <tr style="height:40px">
      <th class="gdfColHeader" style="width:35px;"></th>
      <th class="gdfColHeader" style="width:25px;"></th>
      <th class="gdfColHeader gdfResizable" style="width:30px;">C&oacute;digo</th>
      <th class="gdfColHeader gdfResizable" style="width:10px;"></th>
      <th class="gdfColHeader gdfResizable" style="width:200px;">Nombre</th>
      <th class="gdfColHeader gdfResizable" style="width:80px;">Comienzo</th>
      <th class="gdfColHeader gdfResizable" style="width:80px;">Fin</th>
      <th class="gdfColHeader gdfResizable" style="width:50px;">Duraci&oacute;n</th>
      <th class="gdfColHeader gdfResizable" style="width:50px;">Depende de</th>
      <th class="gdfColHeader gdfResizable" style="width:200px;"></th>
    </tr>
    </thead>
  </table>
  --></div>

  <div class="__template__" type="TASKROW"><!--
  <tr taskId="(#=obj.id#)" class="taskEditRow" level="(#=level#)">
    <th class="gdfCell edit" align="right" style="cursor:pointer;"><span class="taskRowIndex">(#=obj.getRow()+1#)</span> <span class="teamworkIcon" style="font-size:12px;" >e</span></th>
    <td class="gdfCell" align="center"><div class="taskStatus cvcColorSquare" status="(#=obj.status#)"></div></td>
    <td class="gdfCell"><input type="text" name="code" value="(#=obj.code?obj.code:''#)"></td>
    <td class="gdfCell"><span>-</span></td>

    <td class="gdfCell indentCell" style="padding-left:(#=obj.level*10#)px;"><input type="text" name="name" value="(#=obj.name#)" style="(#=obj.level>0?'border-left:2px dotted orange':''#)"></td>

    <td class="gdfCell"><input type="text" name="start"  value="" class="date"></td>
    <td class="gdfCell"><input type="text" name="end" value="" class="date"></td>
    <td class="gdfCell"><input type="text" name="duration" value="(#=obj.duration#)"></td>
    <td class="gdfCell"><input type="text" name="depends" value="(#=obj.depends#)" (#=obj.hasExternalDep?"readonly":""#)></td>
    <td class="gdfCell taskAssigs">(#=obj.getAssigsString()#)</td>
  </tr>
  --></div>

  <div class="__template__" type="TASKEMPTYROW"><!--
  <tr class="taskEditRow emptyRow" >
    <th class="gdfCell" align="right"></th>
    <td class="gdfCell" align="center"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
    <td class="gdfCell"></td>
  </tr>
  --></div>

  <div class="__template__" type="TASKBAR"><!--
  <div class="taskBox" taskId="(#=obj.id#)" >
    <div class="layout (#=obj.hasExternalDep?'extDep':''#)">
      <div class="taskStatus" status="(#=obj.status#)"></div>
      <div class="taskProgress" style="width:(#=obj.progress>100?100:obj.progress#)%; background-color:(#=obj.progress>100?'red':'rgb(153,255,51);'#);"></div>
      <div class="milestone (#=obj.startIsMilestone?'active':''#)" ></div>

      <div class="taskLabel"></div>
      <div class="milestone end (#=obj.endIsMilestone?'active':''#)" ></div>
    </div>
  </div>
  --></div>


  <div class="__template__" type="CHANGE_STATUS"><!--
    <div class="taskStatusBox">
      <div class="taskStatus cvcColorSquare" status="STATUS_ACTIVE" title="Activa"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_DONE" title="Completa"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_FAILED" title="Incorrecta"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_SUSPENDED" title="Suspendia"></div>
      <div class="taskStatus cvcColorSquare" status="STATUS_UNDEFINED" title="Indefinida"></div>
    </div>
  --></div>


  <div class="__template__" type="TASK_EDITOR"><!--
  <div class="ganttTaskEditor">
  <table width="100%">
    <tr>
      <td>
        <table cellpadding="5">
          <tr>
            <td><label for="code">C&oacute;digo</label><br><input type="text" name="code" id="code" value="" class="formElements"></td>
           </tr><tr>
            <td><label for="name">Nombre</label><br><input type="text" name="name" id="name" value=""  size="35" class="formElements"></td>
          </tr>
          <tr></tr>
            <td>
              <label for="description">Descripci&oacute;n</label><br>
              <textarea rows="5" cols="30" id="description" name="description" class="formElements"></textarea>
            </td>
          </tr>
        </table>
      </td>
      <td valign="top">
        <table cellpadding="5">
          <tr>
          <td colspan="2"><label for="status">Estado</label><br><div id="status" class="taskStatus" status=""></div></td>
          <tr>
          <td colspan="2"><label for="progress">Progreso</label><br><input type="text" name="progress" id="progress" value="" size="3" class="formElements">%</td>
          </tr>
          <tr>
          <td><label for="start">Comienzo</label><br><input type="text" name="start" id="start"  value="" class="date" size="10" class="formElements"><input type="checkbox" id="startIsMilestone"> </td>
          <td rowspan="2" class="graph" style="padding-left:50px"><label for="duration">dur.</label><br><input type="text" name="duration" id="duration" value=""  size="5" class="formElements"></td>
        </tr><tr>
          <td><label for="end">Fin</label><br><input type="text" name="end" id="end" value="" class="date"  size="10" class="formElements"><input type="checkbox" id="endIsMilestone"></td>
        </table>
      </td>
    </tr>
    </table>

    

  <div style="text-align: right; padding-top: 20px"><button id="saveButton" class="button big" name="guardaTarea">Aceptar</button></div>
  </div>
  --></div>


  <div class="__template__" type="ASSIGNMENT_ROW"><!--
  <tr taskId="(#=obj.task.id#)" assigId="(#=obj.assig.id#)" class="assigEditRow" >
    <td ><select name="resourceId"  class="formElements" (#=obj.assig.id.indexOf("tmp_")==0?"":"disabled"#) ></select></td>
    <td ><select type="select" name="roleId"  class="formElements"></select></td>
    <td ><input type="text" name="effort" value="(#=getMillisInHoursMinutes(obj.assig.effort)#)" size="5" class="formElements"></td>
    <td align="center"><span class="teamworkIcon delAssig" style="cursor: pointer">d</span></td>
  </tr>
  --></div>

</div>
<script type="text/javascript">
  $.JST.loadDecorator("ASSIGNMENT_ROW", function(assigTr, taskAssig) {

    var resEl = assigTr.find("[name=resourceId]");
    for (var i in taskAssig.task.master.resources) {
      var res = taskAssig.task.master.resources[i];
      var opt = $("<option>");
      opt.val(res.id).html(res.name);
      if (taskAssig.assig.resourceId == res.id)
        opt.attr("selected", "true");
      resEl.append(opt);
    }


    var roleEl = assigTr.find("[name=roleId]");
    for (var i in taskAssig.task.master.roles) {
      var role = taskAssig.task.master.roles[i];
      var optr = $("<option>");
      optr.val(role.id).html(role.name);
      if (taskAssig.assig.roleId == role.id)
        optr.attr("selected", "true");
      roleEl.append(optr);
    }

    if (taskAssig.task.master.canWrite) {
      assigTr.find(".delAssig").click(function() {
        var tr = $(this).closest("[assigId]").fadeOut(200, function() {
          $(this).remove();
        });
      });
    }


  });
</script>



</body>
</html>