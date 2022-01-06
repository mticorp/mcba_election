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
                                <form id="addExcel">
                                    @csrf
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="fileUpload">
                                            <label class="custom-file-label" for="fileUpload">Choose Excel file</label>
                                            <p class="mt-2 hidden loading text-info"><i class="fas fa-spinner fa-spin"></i> Please wait while reading your excel file!</p>
                                        </div>
                                    </div>
                                    <div class="form-group text-right">
                                        <a href="{{route('member-excel-download')}}" class="btn btn-flat btn-info float-left"><i class="fa fa-download" aria-hidden="true"></i> Download Excel Template</a>
                                        <button type="button" class="btn btn-flat btn-success" id="import_btn" ><i class="fas fa-upload"></i> Import</button>
                                        <a href="{{route('admin.register.index')}}" type="button" class="btn btn-flat btn-danger"><i class="fas fa-reply-all"></i> Member List</a>
                                    </div>
                                </form>
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
    var error_log = [];
    $(document).ready(function(){
        bsCustomFileInput.init();        
    })

    $("#import_btn").on('click',function(){
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

        $(".loading").removeClass('hidden');

        UploadProcess();
    })

    function UploadProcess() {            
    
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
                $(".loading").addClass('hidden');
                $.unblockUI();
                toastr.error('Info - This browser does not support HTML5.')                
            }
        } else {
            $(".loading").addClass('hidden');
            $.unblockUI();
            toastr.error('Info - Please upload a valid Excel file.')            
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
                if(!nrc_data[state_no].includes(dist))
                {                    
                   error_log.push("Invalid NRC at line - "+index);
                }                
                
                if(el['Phone Number'] != undefined)
                {
                    let phone_no = el['Phone Number'];
                    phone_no = phone_no.replace('-','');
                    phone_no = phone_no.replace(' ','');
                    let split_phone_no = phone_no.split(',');                    
                    let phone_pattern = /09\d{7}/;
                    split_phone_no.forEach(function(phone,key) {
                        if(!phone_pattern.test(phone) || phone.length > 11)
                        {
                            split_phone_no.splice(key,1);
                        }
                    });

                    if(split_phone_no.length < 1)
                    {
                        error_log.push("Invalid Phone at line - " + index);
                    }
                                    
                    el['Phone Number'] = split_phone_no.join(',');                    
                }else{
                    error_log.push("Phone Number is required at line - " + index);
                }

                if(el['Email'] != undefined)
                {
                    let email = el['Email'];
                    email = email.replace('-','');
                    email = email.replace(' ','');
                    if(email.length > 0)
                    {
                        let split_email = email.split(',');
                        let email_pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                        split_email.forEach(function(email,key) {
                            if(!email_pattern.test(email))
                            {
                                split_email.splice(key,1);
                            }
                        });

                        if(split_email.length < 1)
                        {
                            error_log.push("Invalid Email at line - " + index);
                        }

                        el['Email'] = split_email.join(',');
                    }else{
                        el['Email'] = null;
                    }
                }                                            
            })          
            
            if(error_log.length < 1)
            {            
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',                    
                    url: "{{ route('admin.register.excel-import') }}",
                    data: {
                        data:XL_row_object,
                    },                
                    success: function(data) {
                        $(".loading").addClass('hidden');
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
            }else{           
                error_log.forEach(element => {
                    toastr.error('Info - ' + element)
                });
            }   
        })
    };
</script>
@endsection
