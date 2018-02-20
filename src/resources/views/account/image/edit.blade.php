@extends('runsite::layouts.app')
@section('app')

<style>
    .cropper-view-box,
    .cropper-face {
      border-radius: 50%;
    }
</style>
<div class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('runsite::account.image.Edit image') }}</h3>
        </div>
        {!! Form::open(['route'=>'admin.account.image.crop', 'method'=>'patch']) !!}
            {{ csrf_field() }}
            <input type="hidden" name="x">
            <input type="hidden" name="y">
            <input type="hidden" name="width">
            <input type="hidden" name="height">
            <div class="box-body">
                <div class="image-crop-wrapper">
                    <img id="image" src="{{ $authUser->imagePathOriginal() }}" style="max-height: 300px; max-width: 100%;">
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">{{ trans('runsite::account.image.Update image') }}</button>
            </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection

@section('js')
<script>

    $(function () {
      var $image = $('#image');

      $image.cropper({
          aspectRatio: 4 / 4,
          dragMode: 'move',
          viewMode: 2,
          autoCropArea: 0.9,
          movable: false,
          scalable: false,
          zoomable: false,
          minCropBoxWidth: 100,
          minCropBoxHeight: 100,

          crop: function(e) {
            $('input[name=x]').val(e.x);
            $('input[name=y]').val(e.y);
            $('input[name=width]').val(e.width);
            $('input[name=height]').val(e.height);
          }
      });
    });
  </script>
@endsection
