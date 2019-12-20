<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('page/table/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{asset('page/table/vendor/metisMenu/metisMenu.min.css')}}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.css')}}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{asset('page/table/dist/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{asset('page/table/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">共作人员</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            共作人员
                            <a href="{{url('agent/addmember')}}" class="btn btn-success pull-right"  >添加共作人员</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>姓名</th>
                                        <th>电话</th>
                                        <th>时间</th>
                                        <th>性别</th>
                                        <th>年龄</th>
                                        <th>收入</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($list as $key=>$item)
                                    <tr class="odd gradeX">
                                        <td>{{$item['id']}}</td>
                                        <td>{{$item['name']}}</td>
                                        <td>{{$item['phone']}}</td>
                                       
                                        <td>
                                            {{ date('Y-m-d H:i:s',$item['time'])}}
                                           
                                        </td>

                                        <td>
                                            @if($item['sex'] == 1)
                                                男
                                            @else
                                                女
                                            @endif
                                        </td>
                                        
                                        <td>{{$item['age']}}</td>
                                        <td>{{$item['price']}}</td>
                                        <td class="center">
                                            <button type="button" class="btn btn-success" onclick="btnsuc(this)" value="{{$item['id']}}" >点击
                                            @if($item['status']==0)
                                                禁用
                                            @else
                                                启用
                                            @endif
                                            </button>
                                        </td>
                                        
                                        <td class="center">
                                            <a onclick="edit(this)" id="{{$item['id']}}" >编辑</a>
                                        </td>
                                    </tr>
                                   @endforeach
                                   
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{{asset('page/table/vendor/jquery/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{asset('page/table/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{asset('page/table/vendor/metisMenu/metisMenu.min.js')}}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{asset('page/table/vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-plugins/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('page/table/vendor/datatables-responsive/dataTables.responsive.js')}}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{asset('page/table/dist/js/sb-admin-2.js')}}"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });

        


    });

    function btnsuc(obj){
        $.ajax({
            url:"{{url('agent/member')}}",
            data:{'type':'edit','id':obj.value},
            dataType:'json',
            type:'post',
            success:function(res){
                // console.log(res);
                if(res.code==1){
                    alert(res.msg);
                    location.reload();
                }else{
                    alert(res.msg);
                }
            }
        })
    }

    function edit(obj){
        var id=obj.id;
        location.href = "{{url('agent/addmember')}}?type=edit&id="+id;

    }
    </script>

</body>

</html>
