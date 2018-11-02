@if (count($errors) > 0)

<div class="wlog-alert layout-alert">
    <div class="wlog-alert-bar layout-alert-bar">
        <button class="wlog-button wlog-button-small"><i class="fas fa-times"></i></button>
    </div>
    <div class="wlog-alert__inner layout-alert__inner">
        <span>{!! implode('<br>', $errors->all()) !!}</span>
    </div>
</div>

@endif