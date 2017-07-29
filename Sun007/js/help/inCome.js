(function($) {
			var deceleration = mui.os.ios?0.003:0.0009;
			$('.mui-scroll-wrapper').scroll({
				bounce: false,
				indicators: false, //是否显示滚动条
				deceleration:deceleration
			});
			$.each(document.querySelectorAll('.mui-slider-group .mui-scroll'), function(index, pullRefreshEl) {
				$(pullRefreshEl).pullToRefresh({
//					down: {
//						callback: function() {
//							var self = this;
//							setTimeout(function() {
//								var ul = self.element.querySelector('.mui-table-view');
//								ul.insertBefore(createFragment(ul, index, 10, true), ul.firstChild);
//								self.endPullDownToRefresh();
//							}, 1000);
//						}
//					},
					up: {
						callback: function() {
							var self = this;
							setTimeout(function() {
								var ul = self.element.querySelector('.mui-table-view');
								ul.appendChild(createFragment(ul, index, 5));
								self.endPullUpToRefresh();
							}, 1000);
						}
					}
				});
			});
			var createFragment = function(ul, index, count, reverse) {
				var length = ul.querySelectorAll('li').length;
				var fragment = document.createDocumentFragment();
				var li,img,h2,p,div;
				for (var i = 0; i < count; i++) {
					li = document.createElement('li');
					li.className = 'mui-table-view-cell list-li';
					
					img = document.createElement("img");
					img.className = 'list-img';
					img.src = 'images/list.png';
					
					h2 = document.createElement('h2');
					h2.className = 'list-title';
					h2.innerHTML = '【别克 4S 店】 店铺服务考核';
					
					p = document.createElement('p');
					p.className = 'p-list';
					p.innerHTML = '任务范围: 北京市朝阳区所有4S店';
					
					p1 = document.createElement('p');
					p1.className = 'p-list';
					p1.innerHTML = '任务目标: 考核 4S店人员的服务态度';
					
					div = document.createElement('div');
					div.className = 'income-over';
					div.innerHTML = '待结算';
					
					li.appendChild(img);
					li.appendChild(h2);
					li.appendChild(p);
					li.appendChild(p1);
					li.appendChild(div);					
					
					
					fragment.appendChild(li);
				}
				return fragment;
			};
		
			var html2 = '<ul class="mui-table-view"><li class="mui-table-view-cell list-li"><img src="images/list.png" class="list-img"/><h2 class="list-title">【别克 4S 店】 店铺服务考核</h2><p class="p-list">任务范围: 北京市朝阳区所有4S店</p><p class="p-list">任务目标: 考核 4S店人员的服务态度</p><div class="income-over">待结算<div></li><li class="mui-table-view-cell list-li"><img src="images/list.png" class="list-img"/><h2 class="list-title">【别克 4S 店】 店铺服务考核</h2><p class="p-list">任务范围: 北京市朝阳区所有4S店</p><p class="p-list">任务目标: 考核 4S店人员的服务态度</p><div class="income-over">待结算<div></li><li class="mui-table-view-cell list-li"><img src="images/list.png" class="list-img"/><h2 class="list-title">【别克 4S 店】 店铺服务考核</h2><p class="p-list">任务范围: 北京市朝阳区所有4S店</p><p class="p-list">任务目标: 考核 4S店人员的服务态度</p><div class="income-over">待结算<div></li></ul>';
			var item2 = document.getElementById('item2mobile');
			document.getElementById('slider').addEventListener('slide', function(e) {
				if (e.detail.slideNumber === 1) {
					if (item2.querySelector('.mui-loading')) {
						setTimeout(function() {
							item2.querySelector('.mui-scroll').innerHTML = html2 + '<div class="mui-pull-bottom-tips"><div class="mui-pull-bottom-wrapper"><span class="mui-pull-loading">上拉显示更多</span></div></div>';
						}, 500);
					}
				}
			});

		})(mui);