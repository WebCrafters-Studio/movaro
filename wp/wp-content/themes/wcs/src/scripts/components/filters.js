import noUiSlider from 'nouislider';

document.addEventListener('DOMContentLoaded', () => {
	const blocks = document.querySelectorAll('[data-filter-block]');
	if (!blocks.length) return;

	blocks.forEach(block => {
		const selects = block.querySelectorAll('[data-filter-select]');
		if (!selects.length) return;
		block.activeFilters = {};
		block.rangePrice = {};
		block.page = null;

		const defaultSelect = block.parentElement.querySelector('form.woocommerce-ordering select');

		if(defaultSelect){
			if(block.classList.contains('c-filters')){
				block.querySelector('[data-filter-content]').prepend(createCustomSelect(defaultSelect,block.dataset.mainClass,true,true));
			}else if(block.classList.contains('c-filters-sidebar')){
				block.querySelector('[data-filter-content]').prepend(createCustomSelect(defaultSelect,block.dataset.mainClass,true,false));
			}
		}

		customSelects(block);
		inputRange(block)

		updateSelectCount()
		const showMoreBtn = block.querySelector('[data-filter-show-more]');
		if(showMoreBtn){
			showMoreBtn.addEventListener('click',()=>{
				const hideSelects = block.querySelectorAll('[data-filter-select].hidden');
				hideSelects.forEach(select => {
					select.classList.remove('hidden');
				});
				showMoreBtn.classList.remove('show');
				showMoreBtn.setAttribute('data-filter-show-more','true')
			})
		}

		applyFiltersFromURL(block);
	});
})

// document.addEventListener('click',(e)=>{
// 	console.log(e.target);
// })

document.addEventListener('click', (e) => {
	const activeSelects = document.querySelectorAll('[data-filter-options].active');
	if (activeSelects.length) {
		activeSelects.forEach(select => {
			if (!select.contains(e.target) && !select.previousElementSibling.contains(e.target)) {
				select.classList.remove('active');
				select.previousElementSibling.querySelector('[data-filter-open]').classList.remove('active');				
			}
		})
	}

	const link = e.target.closest('[data-filter-product-pagination] a');
    if (link){ 

		e.preventDefault();

		const url = new URL(link.href);
		let page = url.searchParams.get('paged');

		if (!page) {
			const match = link.href.match(/\/page\/(\d+)/);
			page = match ? match[1] : 1;
		}
		const blocks = document.querySelectorAll('[data-filter-block]');
		blocks.forEach(block => {
			block.page = page;
		});

		filterProduct(block.activeFilters,block.rangePrice,page);
	}
});

window.addEventListener('resize',updateSelectCount);

function updateSelectCount(){
	const block = document.querySelector('[data-filter-block]');
	const items = block.querySelectorAll('[data-filter-select]');
	const showMoreBtn = block.querySelector('[data-filter-show-more]');
	if(!block || !items.length || !showMoreBtn) return;
	const mobileTextBtn = showMoreBtn.dataset.mobileText;
	const desktopTextBtn = showMoreBtn.dataset.desktopText;
	const isShowMore = showMoreBtn.dataset.filterShowMore === 'true';
	const firstItemTop = items[0].offsetTop;
	
	if(isShowMore || items.length<=1) return;

	showMoreBtn.classList.add('show');

	if(window.innerWidth < 576){
		showMoreBtn.innerHTML = mobileTextBtn;
		items.forEach((item,index) => {
			if(index!=0){
				item.classList.add('hidden');
			}
		});		
	}else{
		showMoreBtn.innerHTML = desktopTextBtn;

		items.forEach(item => {
			item.classList.remove('hidden');
			if(item.offsetTop != firstItemTop){
				item.classList.add('hidden');
			}
		});
	}

	const hiddenSelects = block.querySelectorAll('[data-filter-select].hidden');
	
	if(!hiddenSelects.length){
		showMoreBtn.classList.remove('show');
	}

}

function createCustomSelect(select,mainClass,checkFirstOption,showArrow){
	if(!select && !mainClass) return;
	const catName = select.name;
	const options = select.options;
	
	const customSelect = document.createElement('div');
	customSelect.classList.add(`${mainClass}__select`);
	customSelect.setAttribute('data-filter-select','');
	customSelect.setAttribute('data-category-slug',catName);
	
	const label = document.createElement('div');
	label.classList.add(`${mainClass}__label`);
	label.setAttribute('data-filter-label','');

	const labelText = document.createElement('div');
	labelText.classList.add(`${mainClass}__text`);
	labelText.setAttribute('data-filter-text',select.options[select.selectedIndex].text);
	labelText.textContent=select.options[select.selectedIndex].text;

	const arrow = '<svg class="arrow" data-filter-open width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.9159 4.78916C10.8617 4.73448 10.7972 4.69108 10.7261 4.66147C10.655 4.63185 10.5788 4.61661 10.5018 4.61661C10.4248 4.61661 10.3485 4.63185 10.2774 4.66147C10.2063 4.69108 10.1418 4.73448 10.0876 4.78916L7.41593 7.46082C7.3617 7.5155 7.29719 7.55889 7.2261 7.58851C7.15502 7.61812 7.07877 7.63337 7.00176 7.63337C6.92476 7.63337 6.84851 7.61812 6.77743 7.58851C6.70634 7.55889 6.64183 7.5155 6.5876 7.46082L3.91593 4.78916C3.8617 4.73448 3.79719 4.69108 3.7261 4.66147C3.65502 4.63185 3.57877 4.61661 3.50176 4.61661C3.42476 4.61661 3.34851 4.63185 3.27743 4.66147C3.20634 4.69108 3.14183 4.73448 3.0876 4.78916C2.97895 4.89845 2.91797 5.0463 2.91797 5.20041C2.91797 5.35452 2.97895 5.50236 3.0876 5.61166L5.7651 8.28916C6.09322 8.61687 6.53801 8.80095 7.00176 8.80095C7.46552 8.80095 7.91031 8.61687 8.23843 8.28916L10.9159 5.61166C11.0246 5.50236 11.0856 5.35452 11.0856 5.20041C11.0856 5.0463 11.0246 4.89845 10.9159 4.78916Z" fill="currentColor"/></svg>';

	const optionsWrap = document.createElement('div');
	optionsWrap.classList.add(`${mainClass}__options`);
	optionsWrap.setAttribute('data-filter-options','');
	
	Array.from(options).forEach((opt,index) => {
		const option = document.createElement('div');
		option.classList.add(`${mainClass}__option`);
		option.setAttribute('data-filter-option','');
		option.setAttribute('data-filter-slug',opt.value);
		option.setAttribute('data-filter-name',opt.text);
		
		const optionInput = document.createElement('input');
		optionInput.type='radio';
		optionInput.name=catName;
		optionInput.id='filter-'+opt.value;
		if(index===0 && checkFirstOption==true){
			optionInput.checked = true;
		}
		
		const optionLabel = document.createElement('label');
		optionLabel.setAttribute('for','filter-'+opt.value)
		optionLabel.textContent=opt.text;
		
		option.appendChild(optionInput);
		option.appendChild(optionLabel);
		
		optionsWrap.appendChild(option);
	});

	label.appendChild(labelText);
	if(showArrow){
		label.insertAdjacentHTML('beforeend',arrow);
	}
	customSelect.appendChild(label);
	customSelect.appendChild(optionsWrap);
	select.remove();	

	return customSelect;
}

function customSelects(block) {
	const selects = block.querySelectorAll('[data-filter-select]');
	if (!selects.length) return;
	selects.forEach(select => {
		const label = select.querySelector('[data-filter-label]');
		const arrow = label.querySelector('[data-filter-open]');
		const optionsWrap = select.querySelector('[data-filter-options]');
		const options = select.querySelectorAll('[data-filter-option]');
		let labelText;
		if(label){
			labelText = label.querySelector('[data-filter-text]') ? label.querySelector('[data-filter-text]').dataset.filterText : '';
		}
		
		label.addEventListener('click', () => {
			if (optionsWrap.classList.contains('active')) {
				optionsWrap.classList.remove('active');
				arrow.classList.remove('active');
			} else {
				optionsWrap.classList.add('active');
				arrow.classList.add('active');
			}

		});
		options.forEach(option => {
			option.addEventListener('click', () => {
				const input = option.querySelector('[data-filter-option] input');
				input.addEventListener('change', () => {
					const activeRadioInput = select.querySelector('.c-filters [data-filter-option] input[type="radio"]:checked');
					if (activeRadioInput) {
						let nextLabel = activeRadioInput.parentElement.dataset.filterName;
						label.querySelector('[data-filter-text]').innerHTML = nextLabel;
					} else {
						if(labelText){
							label.querySelector('[data-filter-text]').innerHTML = labelText;
						}
					}					
					
					const activeInputs = Array.from(select.querySelectorAll('[data-filter-option] input:checked'));
					const catSlug = select.dataset.categorySlug;
					const catToFilter = activeInputs.map(input => input.parentElement.dataset.filterSlug);
					block.activeFilters[catSlug] = [];
					if (activeInputs.length > 0) {
						block.activeFilters[catSlug] = catToFilter;
					}else{
						block.activeFilters[catSlug] = [];
					}
					filterProduct(block.activeFilters);
				});
			});
		})
	});
}

function inputRange(block){
	const inputWrap = block.querySelector('[data-filter-inputs]');
	if(!inputWrap)return;
	const inputStart = block.querySelector('[data-filter-input-start]');
	const inputEnd = block.querySelector('[data-filter-input-end]');
	const range = block.querySelector('[data-filter-range]');
	const minVal = Number(inputWrap.dataset.minPrice);
    const maxVal = Number(inputWrap.dataset.maxPrice);
    const startVal = Number(inputStart.value);
    const endVal = Number(inputEnd.value);

    noUiSlider.create(range, {
        start: [startVal, endVal],
        connect: true,
        step: 1,
        range: {
            'min': minVal,
            'max': maxVal
        }
    });

	let rangePrice = [startVal, endVal];
	
	inputStart.addEventListener('change', function() {
        range.noUiSlider.set([this.value, null]);
		block.rangePrice = rangePrice;
		filterProduct(block.activeFilters, rangePrice);
    });

    inputEnd.addEventListener('change', function() {
        range.noUiSlider.set([null, this.value]);
		block.rangePrice = rangePrice;
		filterProduct(block.activeFilters, rangePrice);
    });

    range.noUiSlider.on('update', function(values, handle) {
		rangePrice = [Number(inputStart.value), Number(inputEnd.value)];
        if (handle === 0) {
            inputStart.value = Math.round(values[0]);
			inputStart.setAttribute('data-filter-input-start',inputStart.value)
        } else {
            inputEnd.value = Math.round(values[1]);
			inputEnd.setAttribute('data-filter-input-end',inputEnd.value)
        }
    });
	
    range.noUiSlider.on('change', function(values, handle) {
        if (handle === 0) {
			inputStart.dispatchEvent(new Event('change'));
        } else {
			inputEnd.dispatchEvent(new Event('change'));
        }
    });

}


function filterProduct(categoryFilters, rangePrice,page) {
	updateSelectCount();
	const block = document.querySelector('[data-filter-block]');
	const container = document.querySelector('[data-filter-product-container]');
	const containerWrap = document.querySelector('[data-filter-product-wrap]');
	if(!block || !container) return;	
	const blockTerm = block.dataset.filterBlock;
	const pagination = document.querySelector('[data-filter-product-pagination]');

	const load = block.querySelector('[data-filter-load]') ?? containerWrap.querySelector('[data-filter-load]') ?? '';
	const options = block.querySelectorAll('[data-filter-option] input');

	const inputStart = block.querySelector('[data-filter-input-start]');
	const inputEnd = block.querySelector('[data-filter-input-end]');
	const range = block.querySelector('[data-filter-range]');

	if(inputStart){
		inputStart.style.pointerEvents='none'
		inputStart.style.cursor = "not-allowed";
	}
	if(inputEnd){
		inputEnd.style.pointerEvents='none'
		inputEnd.style.cursor = "not-allowed";
	}
	if(range){
		range.style.pointerEvents='none'
		range.style.cursor = "not-allowed";
	}

	options.forEach(option=>{
		option.style.pointerEvents='none'
		option.style.cursor = "not-allowed";
	});

	container.style.pointerEvents = "none";
	if(containerWrap){
		containerWrap.style.position = 'relative';
		load.classList.add("visible");
		containerWrap.prepend(load);
	}else{
		load.classList.add("visible");
	}
	block.style.pointerEvents = "none";
	block.style.cursor = "not-allowed";

	container.style.transition = 'all 0.6s ease';
	container.style.opacity = '0';

	const params = new URLSearchParams();
	if(!rangePrice){
		rangePrice = block.rangePrice;
	}
	if (rangePrice) {
		if(rangePrice[0] !=0 ){
			params.set('min_price', rangePrice[0]);
		}
		if(rangePrice[1] !=0 ){
			params.set('max_price', rangePrice[1]);
		}
	}
	if(!page){
		page = block.page ?? 1
	}
	if(page>1){
		params.set('paged', page);
	}

	Object.keys(categoryFilters).forEach(key => {
		if (categoryFilters[key].length > 0) {
			const urlKey = (key === 'product_cat') ? 'f_cat' : key;
			params.set(urlKey, categoryFilters[key].join(','));
		}
	});

	const data = new FormData();
    data.append('action', 'my_filter_products');
    data.append('nonce', my_ajax_filters.nonce);
	if(blockTerm){
		data.append('term', blockTerm);
	}
    
	window.history.pushState({}, '', window.location.pathname + '?' + params.toString());

	fetch(my_ajax_filters.ajax_url + '?' + params.toString(), {
		method: 'POST',
		body: data,
	})
	.then(response => {
		if (!response.ok) throw new Error('Błąd sieci/serwera');
		return response.json();
	})
	.then(res => {
		if(res.success && res.data.products!=''){
			container.innerHTML=res.data.products
			if(res.data.pagination!=''){
				pagination.innerHTML=res.data.pagination
			}
		}
		else{
			container.innerHTML = res.data.message;
		}
		console.log(res);
	})
	.catch(error => console.error('error: ', error))
	.finally(() => {
		setTimeout(() => {
			load.classList.remove("visible");
			block.classList.remove('inactive');
			container.style.opacity = '1';

			block.removeAttribute('style');
			// selects.forEach(select=>select.removeAttribute('style'));
			options.forEach(option=>option.removeAttribute('style'));
			container.removeAttribute('style');

			if(inputStart){
				inputStart.removeAttribute('style');
			}
			if(inputEnd){
				inputEnd.removeAttribute('style');
			}
			if(range){
				range.removeAttribute('style');
			}

		}, 300);
	})
}

function applyFiltersFromURL(block) {
	const params = new URLSearchParams(window.location.search);
	const categoryFilters = {};
	let hasFilters = false;

	for (const [key, value] of params.entries()) {
		if (!['action', 'paged', 'term', 'min_price', 'max_price'].includes(key)) {
			categoryFilters[key] = value.split(',');
			hasFilters = true;
		}
	}

	if (!hasFilters && !params.get('min_price') && !params.get('max_price') && !params.get('term')) {
		return;
	}

	Object.keys(categoryFilters).forEach(taxName => {
		const terms = categoryFilters[taxName];
		terms.forEach(slug => {
			const option = block.querySelector(`[data-filter-option][data-filter-slug="${slug}"] input`);
			if (option) {
				option.checked = true;
			}
		});
	});
	const page = params.get('paged') ?? 1;

	const minPrice = params.get('min_price');
	const maxPrice = params.get('max_price');
	
	if (minPrice !== null || maxPrice !== null) {
		const inputStart = block.querySelector('[data-filter-input-start]');
		const inputEnd = block.querySelector('[data-filter-input-end]');
		const range = block.querySelector('[data-filter-range]');
		
		if (inputStart && minPrice !== null) inputStart.value = minPrice;
		if (inputEnd && maxPrice !== null) inputEnd.value = maxPrice;
		
		if (range && range.noUiSlider) {
			setTimeout(() => {
				const startVal = minPrice !== null ? minPrice : block.querySelector('[data-filter-input-start]').value;
				const endVal = maxPrice !== null ? maxPrice : block.querySelector('[data-filter-input-end]').value;
				range.noUiSlider.set([startVal, endVal]);
			}, 150);
		}
	}

	block.activeFilters = categoryFilters;
	
	setTimeout(() => {
		let rangePrice;
		
		const range = block.querySelector('[data-filter-range]');
		if (range && range.noUiSlider) {
			const sliderValues = range.noUiSlider.get();
			rangePrice = [Math.round(sliderValues[0]), Math.round(sliderValues[1])];
		} else {
			const inputStart = block.querySelector('[data-filter-input-start]');
			const inputEnd = block.querySelector('[data-filter-input-end]');
			rangePrice = [
				inputStart ? parseInt(inputStart.value) || 0 : 0,
				inputEnd ? parseInt(inputEnd.value) || 0 : 0
			];
		}
		
		block.rangePrice = rangePrice;
		block.page = page;

		filterProduct(categoryFilters, rangePrice, page);
	}, 300);
}