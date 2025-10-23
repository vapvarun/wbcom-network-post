/**
 * Network Posts Elementor Widget Handler
 * Compatible with Elementor 3.32.5+
 *
 * @since 1.0.0
 */
(function($) {
	'use strict';

	var NetworkPostsHandler = function($scope) {
		var $container = $scope.find('.elementor-posts-masonry');
		var isMasonry = $container.length > 0;
		var $gridContainer = isMasonry ? $container : $scope.find('.elementor-grid');

		if (!$gridContainer.length) {
			return;
		}

		var $items = $gridContainer.find('.netsposts-content');

		if (!$items.length) {
			return;
		}

		// Show loader initially
		$scope.addClass('netsposts-loading');

		// Get settings from data attribute or Elementor frontend
		var widgetSettings = {};
		try {
			if (typeof elementorFrontend !== 'undefined') {
				var elementSettings = $scope.data('settings') || {};
				widgetSettings = elementSettings;
			}
		} catch (e) {
			console.log('Could not get Elementor settings:', e);
		}

		// Load More functionality
		var currentPage = 1;
		var isLoading = false;
		var hasMorePosts = true;
		var $loadMoreBtn = null;

		// Check if Load More is enabled
		var isLoadMoreEnabled = widgetSettings.pagination_type === 'load_more_on_click';

		// Get columns from Elementor's responsive classes (defined early for skeleton)
		var getColumns = function() {
			var windowWidth = $(window).width();
			var columns = 3; // default

			// Mobile
			if (windowWidth < 768) {
				var mobileClass = $scope.attr('class').match(/elementor-grid-mobile-(\d+)/);
				if (mobileClass) {
					columns = parseInt(mobileClass[1]);
				}
			}
			// Tablet
			else if (windowWidth < 1025) {
				var tabletClass = $scope.attr('class').match(/elementor-grid-tablet-(\d+)/);
				if (tabletClass) {
					columns = parseInt(tabletClass[1]);
				}
			}
			// Desktop
			else {
				var desktopClass = $scope.attr('class').match(/elementor-grid-(\d+)/);
				if (desktopClass) {
					columns = parseInt(desktopClass[1]);
				}
			}

			return columns;
		};

		// Add skeleton loader for initial load
		function addSkeletonLoader() {
			var columns = getColumns();
			var skeletonHTML = '<div class="netsposts-skeleton-loader"><div class="elementor-grid elementor-grid-' + columns + '">';

			// Generate skeleton items based on columns
			var itemCount = Math.min(columns * 2, 6); // Show 2 rows or max 6 items
			for (var i = 0; i < itemCount; i++) {
				skeletonHTML += '<div class="netsposts-skeleton-item">';
				skeletonHTML += '<div class="netsposts-skeleton-image"></div>';
				skeletonHTML += '<div class="netsposts-skeleton-content">';
				skeletonHTML += '<div class="netsposts-skeleton-title"></div>';
				skeletonHTML += '<div class="netsposts-skeleton-text"></div>';
				skeletonHTML += '<div class="netsposts-skeleton-text"></div>';
				skeletonHTML += '<div class="netsposts-skeleton-text"></div>';
				skeletonHTML += '</div></div>';
			}

			skeletonHTML += '</div></div>';
			$scope.prepend(skeletonHTML);
		}

		// Add skeleton loader immediately
		addSkeletonLoader();

		if (isLoadMoreEnabled) {
			initLoadMore();
		}

		function initLoadMore() {
			// Create Load More button
			var buttonText = widgetSettings.load_more_button_text || 'Load More';
			var loadingText = widgetSettings.load_more_loading_text || 'Loading...';
			var noMoreText = widgetSettings.load_more_no_more_text || 'No More Posts';

			$loadMoreBtn = $('<div class="elementor-pagination"><button class="netsposts-load-more-btn">' + buttonText + '</button></div>');
			$scope.find('.netsposts-items').after($loadMoreBtn);

			// Store texts for later use
			$loadMoreBtn.data({
				'default-text': buttonText,
				'loading-text': loadingText,
				'no-more-text': noMoreText
			});

			// Click handler
			$loadMoreBtn.on('click', '.netsposts-load-more-btn', function(e) {
				e.preventDefault();
				if (!isLoading && hasMorePosts) {
					loadMorePosts();
				}
			});
		}

		function loadMorePosts() {
			if (!window.netspostsAjax) {
				console.error('netspostsAjax is not defined');
				return;
			}

			isLoading = true;
			var $btn = $loadMoreBtn.find('.netsposts-load-more-btn');
			$btn.prop('disabled', true).text($loadMoreBtn.data('loading-text'));

			// Add AJAX loading state with spinner
			$scope.addClass('netsposts-ajax-loading');

			$.ajax({
				url: netspostsAjax.ajax_url,
				type: 'POST',
				data: {
					action: 'netsposts_load_more',
					nonce: netspostsAjax.nonce,
					page: currentPage + 1,
					widget_id: $scope.data('id'),
					settings: JSON.stringify(widgetSettings)
				},
				success: function(response) {
					if (response.success && response.data.html) {
						// Extract only .netsposts-content items from response
						var $newItems = $(response.data.html).filter('.netsposts-content');
						if ($newItems.length === 0) {
							$newItems = $(response.data.html).find('.netsposts-content');
						}

						if ($newItems.length > 0) {
							// Append new items
							$gridContainer.append($newItems);

							// Update current page
							currentPage = response.data.next_page - 1;

							// Check if there are more posts
							hasMorePosts = response.data.has_more;

							// Re-run masonry if enabled
							if (isMasonry) {
								// Wait for images in new items to load
								var $newImages = $newItems.find('img');
								if ($newImages.length > 0) {
									var loaded = 0;
									$newImages.each(function() {
										var img = this;
										if (img.complete) {
											loaded++;
											if (loaded === $newImages.length) {
												runMasonry();
											}
										} else {
											$(img).on('load error', function() {
												loaded++;
												if (loaded === $newImages.length) {
													runMasonry();
												}
											});
										}
									});
								} else {
									runMasonry();
								}
							}

							// Update button state
							if (!hasMorePosts) {
								$btn.text($loadMoreBtn.data('no-more-text')).prop('disabled', true);
							} else {
								$btn.text($loadMoreBtn.data('default-text')).prop('disabled', false);
							}
						} else {
							hasMorePosts = false;
							$btn.text($loadMoreBtn.data('no-more-text')).prop('disabled', true);
						}
					} else {
						hasMorePosts = false;
						$btn.text($loadMoreBtn.data('no-more-text')).prop('disabled', true);
					}
				},
				error: function() {
					console.error('AJAX request failed');
					$btn.text($loadMoreBtn.data('default-text')).prop('disabled', false);
				},
				complete: function() {
					isLoading = false;
					// Remove AJAX loading state
					$scope.removeClass('netsposts-ajax-loading');
				}
			});
		}

		// Get gaps from CSS variables
		var getRowGap = function() {
			var gap = $scope.css('--grid-row-gap');
			return gap ? parseInt(gap) : 35;
		};

		var getColumnGap = function() {
			var gap = $scope.css('--grid-column-gap');
			return gap ? parseInt(gap) : 30;
		};

		// Run masonry layout
		var runMasonry = function() {
			if (!isMasonry) {
				return;
			}

			var columns = getColumns();
			var rowGap = getRowGap();
			var columnGap = getColumnGap();

			// Set container position
			$container.css({
				'position': 'relative',
				'width': '100%'
			});

			// Get all items (including newly loaded ones)
			$items = $container.find('.netsposts-content');

			// Initialize column heights
			var columnHeights = [];
			for (var i = 0; i < columns; i++) {
				columnHeights[i] = 0;
			}

			// Position each item
			$items.each(function() {
				var $item = $(this);

				// Find shortest column
				var minHeight = Math.min.apply(Math, columnHeights);
				var column = columnHeights.indexOf(minHeight);

				// Calculate width and position
				// Use CSS calc to handle column gaps properly
				var itemWidth = 'calc((100% - ' + (columnGap * (columns - 1)) + 'px) / ' + columns + ')';
				var leftPos = 'calc(' + (column * 100 / columns) + '% + ' + (column * columnGap) + 'px)';

				// Apply styles
				$item.css({
					'position': 'absolute',
					'width': itemWidth,
					'left': leftPos,
					'top': columnHeights[column] + 'px'
				});

				// Update column height
				columnHeights[column] += $item.outerHeight(true) + rowGap;
			});

			// Set container height to tallest column
			var maxHeight = Math.max.apply(Math, columnHeights);
			$container.css('height', maxHeight + 'px');

			// Remove loading state and skeleton
			$scope.removeClass('netsposts-loading');
			$scope.find('.netsposts-skeleton-loader').fadeOut(300, function() {
				$(this).remove();
			});
		};

		// Initialize masonry layout
		if (isMasonry) {
			// Wait for images to load
			var imagesToLoad = $container.find('img').length;
			var imagesLoaded = 0;

			var onImageLoad = function() {
				imagesLoaded++;
				if (imagesLoaded >= imagesToLoad) {
					runMasonry();
				}
			};

			if (imagesToLoad === 0) {
				runMasonry();
			} else {
				$container.find('img').each(function() {
					if (this.complete) {
						onImageLoad();
					} else {
						$(this).on('load error', onImageLoad);
					}
				});
			}

			// Re-run on window resize (debounced)
			var resizeTimer;
			$(window).on('resize.networkPosts', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(runMasonry, 250);
			});
		} else {
			// For regular grid, just remove loading state
			$scope.removeClass('netsposts-loading');
			$scope.find('.netsposts-skeleton-loader').fadeOut(300, function() {
				$(this).remove();
			});
		}

		// Clean up on destroy
		$scope.on('remove', function() {
			$(window).off('resize.networkPosts');
		});
	};

	// Initialize on Elementor frontend
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/network_posts_widget.default',
			NetworkPostsHandler
		);
	});

})(jQuery);
