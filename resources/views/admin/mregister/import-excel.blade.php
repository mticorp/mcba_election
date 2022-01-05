@extends('layouts.back-app')
@section('breadcrumb')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">

        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active"><a href="{{route('admin.election.index')}}">Home</a></li>
            <li class="breadcrumb-item active"><a href="{{route('admin.register.index')}}">Member List</a></li>
            <li class="breadcrumb-item active">Exel Import</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
@endsection
@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-md-8">
                <div class="card card-red card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Member Import Execel
                        </h3>
                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-md-center">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input" id="fileUpload">
                                        <label class="custom-file-label" for="fileUpload">Choose Excel file</label>
                                        </div>
                                </div>
                                <div class="form-group text-right">
                                    <a href="{{route('member-excel-download')}}" class="btn btn-flat btn-info float-left"><i class="fa fa-download" aria-hidden="true"></i> Download Excel Template</a>
                                    <button type="button" class="btn btn-flat btn-success" onclick="UploadProcess()" ><i class="fas fa-upload"></i> Import</button>
                                    <a href="{{route('admin.register.index')}}" type="button" class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Member List</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ExcelTable"></div>
@endsection
@section('javascript')
<script type="text/javascript" src="{{asset('backend/plugins/xlsx/xlxs.full.min.js')}}"></script>
<script type="text/javascript" src="{{asset('backend/plugins/xlsx/jszip.js')}}"></script>
<script src="{{asset('js/mm-nrc.js')}}"></script>  
<script>
    $(document).ready(function(){
        bsCustomFileInput.init();        
    })

    function UploadProcess() {
        $.blockUI({
            css: {
                backgroundColor:'transparent',
                top: '0px',
                left: '0px',
                width: $(document).width(),
                height: $(document).height(),
                padding: '20%',
            },
            baseZ: 2000,
            message: '<img src="{{ url("images/loader.gif") }}" width="150" />',
        });
        //Reference the FileUpload element.
        var fileUpload = document.getElementById("fileUpload");
 
        //Validate whether File is valid Excel file.
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if (regex.test(fileUpload.value.toLowerCase())) {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
 
                //For Browsers other than IE.
                if (reader.readAsBinaryString) {
                    reader.onload = function (e) {
                        GetDataFromExcel(e.target.result);
                    };
                    reader.readAsBinaryString(fileUpload.files[0]);
                } else {
                    //For IE Browser.
                    reader.onload = function (e) {
                        var data = "";
                        var bytes = new Uint8Array(e.target.result);
                        for (var i = 0; i < bytes.byteLength; i++) {
                            data += String.fromCharCode(bytes[i]);
                        }
                        GetDataFromExcel(data);
                    };
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
            } else {
                alert("This browser does not support HTML5.");
            }
        } else {
            alert("Please upload a valid Excel file.");
        }
    };

    function GetDataFromExcel(data) {        
        //Read the Excel File data in binary
        var workbook = XLSX.read(data, {
            type: 'binary'
        });        

        workbook.SheetNames.forEach(function(sheetName) {
            // Here is your object            
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
            XL_row_object.forEach(function(el,index) {
                index = index + 2;                
                let nrc = new MMNRC(el['NRC No']);
                nrc = nrc.getFormat(null,index);
                
                let check = /^[A-Za-z][A-Za-z0-9]*$/;
                let state_no = nrc.split('/')[0];
                !check.test(state_no) ? state_no = MMNRC.toEngNum(state_no) : '';
                
                let dist = nrc.split('/')[1].split('(')[0];
                let num = nrc.split(')')[1];
                if(nrc_data[state_no].includes(dist))
                {                    
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: "{{ route('admin.register.excel-import') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            $.unblockUI();
                            if (data.errors) {
                                $('form#addExcel').trigger("reset");
                                toastr.error('Info - ' + data.errors)
                            } else if (data.success) {
                                $('form#addExcel').trigger("reset");
                                toastr.success('Info - ' + data.success)
                            }
                        },
                        error: function(response) {
                            $.unblockUI();
                            if(response['responseJSON'])
                            {
                                toastr.error('Info - ' + response['responseJSON'].message)
                            }else{
                                toastr.error('Info - Something Went Wrong!')
                            }
                        },
                    });
                } else{
                    console.log(state_no + "/" + dist + "(N)" + num + " is Invalid NRC");
                }
            });
        })
    };
</script>
@endsection
