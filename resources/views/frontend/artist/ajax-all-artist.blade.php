@foreach ($content->artistData as $key => $row)
    @include('frontend.components.artist.artist-grid', [
        'detailUrlSlug' => $row->detailUrlSlug,
        'image' => $row->image,
        'name' => $row->name,
    ])
@endforeach
