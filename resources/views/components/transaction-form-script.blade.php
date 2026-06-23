@php
    $contactTypeMap = $transactionTypes->mapWithKeys(function ($t) {
        if ($t->transfer) {
            $allowed = ($t->effect === 'add') ? ['client', 'customer'] : ['vendor', 'employee'];
        } else {
            if ($t->effect === 'add') {
                $allowed = ['client', 'customer'];
            } elseif ($t->effect === 'subtract') {
                $allowed = ['vendor', 'employee', 'other'];
            } else {
                $allowed = [];
            }
        }
        return [$t->slug => $allowed];
    });

    $currencyRatesJson = $activeRates->map->rate_to_bdt->toJson();
@endphp

<script>
console.log('Script partial loaded – start');

// 1. Global data
window.transactionTypeContactMap = {!! $contactTypeMap->toJson() !!};
var rates = {!! $currencyRatesJson !!};

// 2. Exchange rate toggle
(function() {
    var currencyHidden = document.querySelector('input[name="currency"]');
    var rateGroup = document.getElementById('exchange-rate-group');
    var rateInput = document.getElementById('exchange_rate');

    if (currencyHidden) {
        currencyHidden.addEventListener('change', function () {
            var selected = this.value;
            rateGroup.style.display = selected === 'BDT' ? 'none' : 'block';
            if (selected !== 'BDT') {
                rateInput.value = rates[selected] || '';
            }
        });
    }
})();

// 3. Update transaction type contact map (called from global createAndSelectOption)
window.updateTransactionTypeContactMap = function(slug, effect, transfer) {
    if (transfer) {
        window.transactionTypeContactMap[slug] = (effect === 'add')
            ? ['client', 'customer']
            : ['vendor', 'employee'];
    } else {
        if (effect === 'add') {
            window.transactionTypeContactMap[slug] = ['client', 'customer'];
        } else if (effect === 'subtract') {
            window.transactionTypeContactMap[slug] = ['vendor', 'employee', 'other'];
        } else {
            window.transactionTypeContactMap[slug] = [];
        }
    }
    if (typeof filterContacts === 'function') {
        filterContacts();
    }
};

// 4. Contact filtering (only if #contact-group exists)
(function() {
    var contactWrapper = document.querySelector('#contact-group .searchable-select-wrapper');
    if (!contactWrapper) { console.log('No contact group, skipping filter setup'); return; }

    var typeInput = document.querySelector('input[name="type"]');
    if (!typeInput) { console.log('No type input, skipping filter setup'); return; }

    function getContactOptions() {
        return contactWrapper.querySelectorAll('.ss-option');
    }

    window.filterContacts = function() {
        var selectedType = typeInput.value;
        var allowed = selectedType ? (window.transactionTypeContactMap[selectedType] || null) : null;
        var options = getContactOptions();

        options.forEach(function(opt) {
            var contactType = opt.dataset.contactType;
            if (!allowed || allowed.indexOf(contactType) !== -1) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });

        var hidden = contactWrapper.querySelector('input[type="hidden"]');
        var selectedVal = hidden ? hidden.value : '';
        if (selectedVal && allowed) {
            var selector = '.ss-option[data-value="' + selectedVal + '"]';
            var selectedOption = contactWrapper.querySelector(selector);
            if (selectedOption && allowed.indexOf(selectedOption.dataset.contactType) === -1) {
                hidden.value = '';
                var selectedText = contactWrapper.querySelector('.ss-selected-text');
                if (selectedText) selectedText.textContent = 'Select a contact';
                options.forEach(function(o) { o.classList.remove('selected'); });
            }
        }
    };

    typeInput.addEventListener('change', window.filterContacts);
    window.filterContacts();

    var optionsContainer = contactWrapper.querySelector('.ss-options');
    if (optionsContainer) {
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    window.filterContacts();
                }
            });
        });
        observer.observe(optionsContainer, { childList: true });
    }
})();

console.log('Script partial loaded – end');
</script>
