{* Images *}
	{option:widgetPhotogallerySlideshow.images}
		<div class="photogallerySlideshowWrapper photogallerySlideshowWrapperId{$widgetPhotogallerySlideshow.id}">
			
			{* Images *}
			<div class="flexslider">
				<ul class="slides">
					{iteration:widgetPhotogallerySlideshow.images}
							
						<li>

							{* With internal link *}
							{option:widgetPhotogallerySlideshow.images.data.internal_link}
							<a href="{$var|geturl:{$widgetPhotogallerySlideshow.images.data.internal_link.page_id}}" class="linkedImage">
							{/option:widgetPhotogallerySlideshow.images.data.internal_link}

							{* With external link *}
							{option:widgetPhotogallerySlideshow.images.data.external_link}
							<a href="{$widgetPhotogallerySlideshow.images.data.external_link.url}" class="linkedImage targetBlank">
							{/option:widgetPhotogallerySlideshow.images.data.external_link}

							<img src="{$var|createimage:{$widgetPhotogallerySlideshow.images.set_id}:{$widgetPhotogallerySlideshow.images.filename}:{$widgetPhotogallerySlideshowResolution}}" />
							
							{* With internal link *}
							{option:widgetPhotogallerySlideshow.images.data.internal_link}
							</a>
							{/option:widgetPhotogallerySlideshow.images.data.internal_link}

							{* With external link *}
							{option:widgetPhotogallerySlideshow.images.data.external_link}
							</a>
							{/option:widgetPhotogallerySlideshow.images.data.external_link}

							<div class="caption">
								{option:widgetPhotogallerySlideshow.images.title}
									<h3>{$widgetPhotogallerySlideshow.images.title}</h3>
								{/option:widgetPhotogallerySlideshow.images.title}
								{$widgetPhotogallerySlideshow.images.text}
							</div>

						</li>
						
					{/iteration:widgetPhotogallerySlideshow.images}
				</ul>
			</div>
			
		</div>
	{/option:widgetPhotogallerySlideshow.images}