$(document).ready((function(){function a(){var a=$("#frame_images_datatable").data("load-route");return $("#frame_images_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:a,type:"POST",data:{search:$("#search").val()}},dom:"<'row'<'col-sm-12'ti>><'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7 dataTables_pager'p>><'row'<'col-sm-12'ti>><'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7 dataTables_pager'p>>",columns:[{data:"image",name:"image",width:120},{data:"frame_number",name:"frame_number",width:120},{data:"actions",name:"actions",className:"text-end",orderable:!1,width:150}]})}var e=a();$("#search_button").on("click",(function(t){t.preventDefault(),$("#frame_images_datatable").DataTable().destroy(),e=a()})),e.on("click",".delete-resource",(function(a){a.preventDefault();var e=$(this).data("delete-route"),t=$(this).data("row-id");deleteResource(e,t)}))}));