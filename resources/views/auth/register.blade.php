<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Registration Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('templete/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('templete/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('templete/dist/css/adminlte.min.css') }}">
  <style>
    /* CSS untuk menyembunyikan input file asli dan menggantinya dengan elemen placeholder */
    .input-file-wrapper {
        position: relative;
        width: 100%;
    }

    .input-file-wrapper input[type="file"] {
        opacity: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        cursor: pointer;
    }

    .input-file-placeholder {
        border: 1px solid #ccc;
        padding: 10px;
        display: inline-block;
        width: 100%;
        cursor: pointer;
        background-color: white;
        border-radius: 5px;
    }
  </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    TOKO <b>BAROKAH</b>
  </div>
  <div class="card">
        <div class="card-body register-card-body">
            @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            <p class="login-box-msg">Registrasi untuk member baru</p>
            <form action="{{ route('register')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-3">
                    <div class="input-file-wrapper rounded">
                        <div class="input-file-placeholder" id="file-placeholder">Pilih foto...</div>
                        <input type="file" class="form-control" name="foto" id="image" >
                    </div>
                </div>
                {{-- <div class="input-group mb-3">
                    <input type="file" class="form-control" placeholder="File format gambar" name="foto">
                </div> --}}
                @error('foto')
                    <small class="form-text text-danger">{{ $message }}</small> 
                @enderror 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Heri Sasongko" name="nama">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div> 
                </div>
                @error('nama')
                    <small class="form-text text-danger">{{ $message }}</small> 
                @enderror
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="herisasongko" name="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div> 
                </div>
                @error('username')
                    <small class="form-text text-danger">{{ $message }}</small> 
                @enderror
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="*****" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div> 
                </div>
                @error('password')
                    <small class="form-text text-danger">{{ $message }}</small> 
                @enderror
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </div>
                </div>
            </form>
            <div class="social-auth-links text-center">
                <p>- OR -</p>
                <a href="{{ route('login')}}" class="btn btn-block btn-success">
                    Sign In
                </a>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('templete/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('templete/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('templete/dist/js/adminlte.min.js') }}"></script>
<script>
    document.getElementById('image').addEventListener('change', function() {
        var fileName = this.files[0].name;
        document.getElementById('file-placeholder').textContent = fileName;
    });
</script>

</body>
</html>
