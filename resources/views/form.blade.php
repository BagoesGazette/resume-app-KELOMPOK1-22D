@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Upload CV</h1>
        </div>
        <div class="row">
              <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                  <form>
                    <div class="card-header">
                      <h4>Form Upload</h4>
                    </div>
                    <div class="card-body">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFileLangHTML">
                        <label class="custom-file-label" for="customFileLangHTML" data-browse="Bestand kiezen">Choose File</label>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-secondary">Kembali</button>
                      <button class="btn btn-primary">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
    </section>
@endsection