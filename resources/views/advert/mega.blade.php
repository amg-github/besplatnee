<div class="mega-advert" style="text-align: center; border: {{ $advert->megaAdvert->border_width }}px solid {{ $advert->megaAdvert->border_color }};background: {{ $advert->megaAdvert->background_color }};color: {{ $advert->megaAdvert->font_color }};padding: 12px 36px">
	<p style="font-size: {{ $advert->megaAdvert->font_width }}px;line-height: 1.2; white-space: nowrap;">
		{{ $advert->name }}
	</p>
	<p style="font-size: {{ $advert->megaAdvert->font_width }}px;line-height: 1.2; white-space: nowrap;">
		{{ $advert->main_phrase }}
	</p>
	<p style="text-align: right">т. {{ $advert->owner->phone }}</p>
</div>