<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
<section class="vh-100" style="background: radial-gradient(circle, rgb(255, 243, 77) 0%, rgb(61, 127, 224) 100%);">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col col-xl-10">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        </div>
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="https://i1.whakoom.com/large/04/3b/5020ae18cd7449efb368b1201bfca38e.jpg"
                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem; height:760px" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

              <form method="POST" action="{{ route('register.store') }}">
              @csrf
                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0">Comics</span>
                  </div>

  

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign up</h5>

                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label">Nombre:</label>
                    <input type="text" name="name"  placeholder="Introduzca su nombre"class="form-control form-control-lg" />
                    
                  </div>
                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" >Email address</label>
                    <input type="email" name="email"  placeholder="Introduzca email"class="form-control form-control-lg" />
                    
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" >Password</label>
                    <input type="password" name="password"  placeholder="Introduzca Contraseña"class="form-control form-control-lg" />
                    
                  </div>
                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" >Confirm password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña" class="form-control form-control-lg" />
                    
                  </div>

                  <div class="pt-1 mb-4">
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="submit">Register</button>
                  </div>
                  <div class="" style="color: #393f81;">Do you have an account? <a href="/user/login"
                  style="color: #393f81;">Login here</a></div>

                  <a href="#!" class="small text-muted">Terms of use.</a>
                  <a href="#!" class="small text-muted">Privacy policy</a>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



</body>
</html>