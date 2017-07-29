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
					

				var li,h3,ul,li1,i1,span,li2,li3,ul2,li21,li22,a;
				for (var i = 0; i < count; i++) {
					li = document.createElement('li');
					li.className = 'mui-table-view-cell list-li for-task';
					
					h3 = document.createElement("h3");
					h3.className = 'task-title';
					h3.innerHTML = '北京市勤和汽车销售有限公司服务质量考核';
					
					ul = document.createElement("ul");
					ul.className = 'pos-ul';
					
					li1 = document.createElement("li");
					li1.className = 'task-distance';
					
					i1 = document.createElement("i");
					i1.className = 'mui-icon iconfont icon-juli';
					
					span = document.createElement("span");
					span.innerHTML = '6.2km';
					
					li1.appendChild(i1);
					li1.appendChild(span);
					
					li2 = document.createElement("li");
					li2.className = 'fengexian';
					li2.innerHTML = '|';
					
					li3 = document.createElement("li");
					li3.innerHTML = '北京市朝阳区大屯路东地铁口';
					
					ul.appendChild(li1);
					ul.appendChild(li2);
					ul.appendChild(li3);
					
					ul2 = document.createElement("ul");
					ul2.className = 'money-ul';
					
					li21 = document.createElement("li");
					li21.className = 'task-money';
					li21.innerHTML = '200元';
					
					li22 = document.createElement("li");
					li22.className = 'task-main';
					
					a = document.createElement("a");
					a.href = 'taskDeg.html';
					a.innerHTML = '任务要求';
					
					li22.appendChild(a);
					ul2.appendChild(li21);
					ul2.appendChild(li22);
					
					li.appendChild(h3);
					li.appendChild(ul);
					li.appendChild(ul2);
					
					fragment.appendChild(li);
				}
				return fragment;
			};
		
			var html2 = '<ul class="mui-table-view"><li class="mui-table-view-cell list-li for-task"><h3 class="task-title">北京市勤和汽车销售有限公司服务质量考核</h3><ul class="pos-ul"><li class="task-distance"><i class="mui-icon iconfont icon-juli"></i><span>6.2km</span></li><li class="fengexian">|</li><li>北京市朝阳区大屯路东地铁口</li></ul><ul class="money-ul"><li class="task-money">200元</li><li class="task-main"><a href="#">任务要求</a></li></ul></li><li class="mui-table-view-cell list-li for-task"><h3 class="task-title">北京市勤和汽车销售有限公司服务质量考核</h3><ul class="pos-ul"><li class="task-distance"><i class="mui-icon iconfont icon-juli"></i><span>6.2km</span></li><li class="fengexian">|</li><li>北京市朝阳区大屯路东地铁口</li></ul><ul class="money-ul"><li class="task-money">200元</li><li class="task-main"><a href="#">任务要求</a></li></ul></li><li class="mui-table-view-cell list-li for-task"><h3 class="task-title">北京市勤和汽车销售有限公司服务质量考核</h3><ul class="pos-ul"><li class="task-distance"><i class="mui-icon iconfont icon-juli"></i><span>6.2km</span></li><li class="fengexian">|</li><li>北京市朝阳区大屯路东地铁口</li></ul><ul class="money-ul"><li class="task-money">200元</li><li class="task-main"><a href="#">任务要求</a></li></ul></li></ul>';
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