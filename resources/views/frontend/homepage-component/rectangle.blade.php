@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif

<div class="genres section" id="{{ $data->componentSlug }}">
    <div class="container-fluid">
        <div class="slider-header">
            <h5>{{ $data->componentName }}</h5>
            <a href="" class="a d-none">See All</a>
        </div>
    </div>
    <div class="double-img-carousel">
        <div class="owl-carousel owl-theme">
            <?php $i = 1; ?>
            @foreach ($data->componentDynamicGroup->data as $key2 => $row2)
                <?php if ($i % 2 == 1) { ?>
                <div href="" class="item">
                    <?php } ?>
                    @if ($data->componentDynamicGroup->commonDetails->groupType == 2)
                        <a href="{{ $authenticateClass ? 'javascript:void(0)' : $row2->detailUrl }}"
                            class="img-content{{ $authenticateClass }}">
                            <img style="width: 190px;height: 95px;" src="{{ $row2->Icon }}">
                            <div class="img-content-overlay">
                                <p class="s1">{{ $row2->Name }}</p>
                            </div>
                        </a>
                    @else
                        <a href="{{ $row2->detailUrl }}"
                            class="img-content">
                            <img style="width: 190px;height: 95px;" src="{{ $row2->Icon }}">
                            <div class="img-content-overlay">
                                <p class="s1">{{ $row2->Name }}</p>
                            </div>
                        </a>
                    @endif
                    <?php if ($i % 2 == 0 || count($data->componentDynamicGroup->data) == $i) { ?>
                </div>
                <?php } ?>
                <?php $i++; ?>
            @endforeach
        </div>
    </div>
</div>
