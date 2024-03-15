
new Vue({
  el: '#delivery_vue',
  data: {
    delivery_method: null,
    courier: null,
    selected_city: null,
    city_name: null,
    post_code: null,
    selected_office: null,
    office: null,
    datatable: null,
    datatable2: null,
    order_number: null,
    order_items: null,
    total_price: null,
    total_weight: null,
    items_quantity: null,
    item_search_input: null,
    office_code: null,
    payment: null,
    delivery_price: 0,
    final_price: 0,
    showNewItemsList: false,
    free_delivery: false,
    street: null,
    calculateErrorMessage: null,
    hasItemType: false,
    admin_discount_type: null,
    admin_discount_value: 0,
    admin_discount: 0,
    hasColors: false,
    hasVariations: false,
    addItemRoute: null,
  },
  computed: {
    delivery_by_courier: function () {
      return this.delivery_method == "office" || this.delivery_method == "address";
    },
  },
  watch: {
    admin_discount: function (newv, oldv) {
      this.admin_discount = parseFloat(this.admin_discount).toFixed(2);
    },
    showNewItemsList: function (newv, oldv) {
      if (newv) {
        this.datatable2 = this.initDatatable2();
      }
    },
    items_quantity: function (newv, oldv) {
      this.getOrderItems();
      this.getTotalWeight();
      this.getTotalPrice();
    },
    delivery_method: function (newv, oldv) {
      this.destroyCitySelect();
      if (this.courier) {
        this.getCourierCities();
      }
      this.street = null;
      this.destroyOffice();
    },
    delivery_price: function (newv, oldv) {
      this.restoreFinalPrice();
    },
    total_price: function (newv, oldv) {
      this.restoreFinalPrice();
    },
    final_price: function (newv, oldv) {
      this.final_price = parseFloat(this.final_price).toFixed(2);
    },

    courier: function (newv, oldv) {
      var app = this;
      this.destroyCitySelect();
      this.destroyOffice();
      this.$nextTick(() => {
        // this.getCourierCities();
        if (app.delivery_method == 'office') {
          app.getOffice();
        } else if (app.delivery_method == 'address') {
          console.log('Get Officess');
          app.getCourierCities();
        }
      })
    },
    payment: function (newv, oldv) {
      if (this.courier == "speedy") {
        this.calculateSpeedyDelivery();
      }
      else if (this.courier == "econt") {
        this.calculateEcontDelivery();
      }
    },

    selected_city: function (newv, oldv) {
      this.destroyOffice();
      //this.destroyStreet
      if (this.delivery_method == 'office') {
        this.getOffice();
      }
    },
    office: function office(newv, oldv) {
      if (newv != null && newv != '') {
        if (this.courier == 'econt') {
          this.calculateEcontDelivery();
        } else if (this.courier == 'speedy') {
          this.calculateSpeedyDelivery();
        }
      }
    },
    street: function street(newv, oldv) {
      if (newv != null && newv != '') {
        if (this.courier == 'econt') {
          this.calculateEcontDelivery();
        } else if (this.courier == 'speedy') {
          this.calculateSpeedyDelivery();
        }
      }
    },
    free_delivery: function free_delivery(newv, oldv) {
      this.restoreFinalPrice()
    },

  },
  methods: {
    showItems: function () {
      this.showNewItemsList = true;
    },
    calculateDeliveryPrice: function () {
      $('#loadingIndicator').show();
      if (this.courier == "speedy") {
        this.calculateSpeedyDelivery();
      }
      else {
        this.calculateEcontDelivery();
      }
    },
    showUserModal: function () {
      $('#user_modal').modal('show');
    },
    closeUserModal: function () {
      $('#user_modal').modal('hide')
    },
    submitUserModal: function () {
      let app = this;
      const url = $('#user_submit_route').val();
      const names = $('#names').val();
      const email = $('#email').val();
      const phone = $('#phone').val();
      $.ajax({
        url: url,
        type: 'POST',
        data: {
          names: names,
          email: email,
          phone: phone,
          order_number: app.order_number,
        }
      })
        .done(function (data) {
          if (data.success) {
            location.reload();
          } else {
            Swal.fire("Опа", data.message, "error");
          }
        })
        .fail(function (err) {
          Swal.fire("Опа", "Възникна грешка!", "error");
        })
        .always(function () {
          // console.log("complete");
        })

    },
    updateItemQuantity: function (event) {
      let app = this;
      const route = event.currentTarget.getAttribute('data-route');
      const qty = event.currentTarget.value;
      const itemId = event.currentTarget.dataset.itemId;
      const itemType = event.currentTarget.getAttribute('data-item-type');
      let data = null;
      if (itemType) {
        data = {
          qty: qty,
          item_id: itemId,
          variations: true,
          type_id: itemType,
        }
      }
      else {
        data = {
          qty: qty,
          item_id: itemId,
        }
      }
      $.ajax({
        url: route,
        type: 'POST',
        data: data
      })
        .done(function (data) {
          if (data.success) {
            app.datatable.draw()
            ++app.items_quantity;
          } else {
            Swal.fire("Опа", data.message, "error");
          }
        })
        .fail(function (err) {
          Swal.fire("Опа", "Възникна грешка!", "error");
          event.currentTarget.value = qty;
        })
        .always(function () {
          // console.log("complete");
        })
    },

    calculateEcontDelivery: function (office) {
      if (this.office || this.street) {
        var app = this;
        let amount = 0;
        if (this.payment == 1) {
          amount = this.total_price;
        }
        this.$nextTick(() => {
          axios.post('/econt/delivery', {
            city_id: app.selected_city,
            weight: app.total_weight,
            office: app.office,
            amount: amount,
            office_code: app.office_code,
            delivery_method: this.delivery_method,
            free_delivery: app.free_delivery,
            post_code: app.post_code
          }).then(function (response) {
            if (response.data.success) {
              app.delivery_price = parseFloat(response.data.price);
              app.calculateErrorMessage = null;
            } else {
              app.calculateErrorMessage = response.data.message;
              app.delivery_price = 0;
            }
          })["catch"](function (err) {
            console.log(err);
          });
        });
      }
    },

    calculateSpeedyDelivery: function (office) {
      if (this.office || this.street) {
        var app = this;
        let amount = 0;
        if (this.payment == 1) {
          amount = this.total_price;
        }
        this.$nextTick(() => {
          axios.post('/speedy/delivery', {
            city_id: app.selected_city,
            weight: app.total_weight,
            office: app.office,
            amount: amount,
            delivery_method: this.delivery_method,
            free_delivery: app.free_delivery,
            post_code: app.post_code

          }).then(function (response) {
            if (response.data.success) {
              app.delivery_price = parseFloat(response.data.price);
              app.calculateErrorMessage = null;
            } else {
              app.calculateErrorMessage = response.data.message;
              app.delivery_price = 0;
            }
          })["catch"](function (err) {
            console.log(err);
          });
        });
      }
    },
    searchInput: function () {
      if ($("#search_input").val().length >= 3) {
        this.datatable2.draw();
      }
    },

    initDatatable2: function () {
      return $('#new_items').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 25,
        "ajax": {
          "url": $('#new_items').data('route'),
          "type": "POST",
          "data": function (d) {
            d['name'] = $("#search_input").val();
            d['id'] = $('#new_items').data('id');
          },
        },
        "columns": [{
          "data": "item",
        },
        {
          "data": "availability",
        },
        {
          "data": "action",
        }
        ],
      });
    },

    initDatatable: function () {
      return $('#orders_table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 25,
        "ajax": {
          "url": $('#orders_table').data('route'),
          "type": "POST",
          "headers": {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
          },
          "data": function (d) {
            d['id'] = $('#orders_table').data('id');
          },
        },
        "columns": [{
          "data": "image",
        },
        {
          "data": "item",
        },
        {
          "data": "code",
        },
        {
          "data": "qty",
          "width": "10%"
        },
        {
          "data": "price",
        },
        {
          "data": "final_price",
        },
        {
          "data": "action",
          "width": "6%"
        }
        ],

      });
    },
    getTotalPrice: function () {
      let total = 0;
      for (let i = 0; i < this.items.length; i++) {
        total += this.items[i].price * this.items[i].quantity;
      }
      this.total_price = parseFloat(total).toFixed(2);
      this.items_quantity = this.items.length;
    },

    getTotalWeight: function () {
      this.$nextTick(() => {
        let total = 0;
        for (let i = 0; i < this.items_quantity; i++) {
          total += this.items[i].weight * this.items[i].quantity;
        }
        this.total_weight = parseFloat(total).toFixed(2);
      });
    },

    getOrderItems: function () {
      const route = $('#order_items_route').val()
      let app = this;
      axios.get(route)
        .then(response => {
          app.items = response.data;
          app.$nextTick(() => {
            app.getTotalWeight();
            app.getTotalPrice();
          });
        })
        .catch(error => {
          console.error(error);
        });
    },

    addItemBtn: function (event) {
      this.addItemRoute = event.currentTarget.getAttribute('data-add-route');
      this.$nextTick(() => {
        if (event.currentTarget.getAttribute('data-colors-route')) {
          console.log('test ' + event.currentTarget.getAttribute('data-colors-route'))
          $('#add_item_modal').modal('show');
          // this.destroyProductVariations(event);
          return this.initProductVariations(event);
        } else {
          this.addItem(event)
        }
      });
    },

    addItem: function (event) {
      let app = this;
      const route = this.addItemRoute;
      $.ajax({
        url: route,
        type: 'POST',
      })
        .done(function (data) {
          if (data.success) {
            app.datatable2.draw()
            app.datatable.draw()
            ++app.items_quantity;
            app.getTotalWeight();
          } else {
            Swal.fire("Опа", data.message, "error");
          }
        })
        .fail(function (err) {
          Swal.fire("Опа", "Възникна грешка!", "error");
        })
        .always(function () {
          // console.log("complete");
        })
    },

    removeItem: function (event) {
      let app = this;
      const route = event.currentTarget.getAttribute('data-delete-route');
      const type_id = event.currentTarget.getAttribute('data-delete-type');
      let data = null;
      if (type_id) {
        data = {
          variations: true,
          type_id: type_id,
        }
      }
      $.ajax({
        url: route,
        type: 'DELETE',
        data: data
      })
        .done(function (data) {
          if (data.success) {
            app.datatable.draw()
            --app.items_quantity;
          } else {
            Swal.fire("Опа", data.message, "error");
          }
        })
        .fail(function (err) {
          Swal.fire("Опа", "Възникна грешка!", "error");
        })
        .always(function () {
          // console.log("complete");
        })

    },

    destroyProductVariations(event) {
      var colors = $('.js-data-variations-color-ajax').data('select2');
      var sizes = $('.js-data-variations-size-ajax').data('select2');
      var customs = $('.js-data-variations-custom-ajax').data('select2');
      if (typeof colors !== 'undefined' && colors !== null) {
        $('.js-data-variations-color-ajax').select2('destroy');
      }
      if (typeof sizes !== 'undefined' && sizes !== null) {
        $('.js-data-variations-size-ajax').select2('destroy');
      }
      if (typeof customs !== 'undefined' && customs !== null) {
        $('.js-data-variations-custom-ajax').select2('destroy');
      }
    },

    initProductVariations(event) {
      this.getColors(event);
      this.getSizes(event);
      this.getCustomTypes(event);
      //this.submitItemWithVariations(event);
    },

    submitItemWithVariations(event) {
      let app = this;
      const route = this.addItemRoute;
      console.log(route);
      let types = [];
      types.push($('#item_variations_color').val());
      types.push($('#item_variations_size').val());
      types.push($('#item_variations_custom').val());
      $.ajax({
        url: route,
        type: 'POST',
        data: {
          variations: true,
          types: types,
        }
      })
        .done(function (data) {
          if (data.success) {
            app.datatable2.draw()
            app.datatable.draw()
            ++app.items_quantity;
            app.getTotalWeight();
            $('#add_item_modal').modal('hide');
          } else {
            Swal.fire("Опа", data.message, "error");
          }
        })
        .fail(function (err) {
          Swal.fire("Опа", "Възникна грешка!", "error");
        })
        .always(function () {
          // console.log("complete");
        })
    },

    getColors(event) {
      $('.js-data-variations-color-ajax').select2({
        ajax: {
          url: event.currentTarget.getAttribute('data-colors-route'),
          dataType: 'json',
          delay: 100,
          processResults: function (data, params) {
            var results = $.map(data, function (obj) {
              return {
                id: obj.id,
                text: obj.text
              };
            });
            return {
              results: results,
            };
          },
          cache: true
        },
        placeholder: 'Изберете цвят ',
        // minimumInputLength: 2
      });
    },

    getSizes(event) {
      $('.js-data-variations-size-ajax').select2({
        ajax: {
          url: event.currentTarget.getAttribute('data-sizes-route'),
          dataType: 'json',
          delay: 100,
          processResults: function (data, params) {
            var results = $.map(data, function (obj) {
              return {
                id: obj.id,
                text: obj.text
              };
            });
            return {
              results: results,
            };
          },
          cache: true
        },
        placeholder: 'Изберете размер ',
        // minimumInputLength: 2
      });
    },
    getCustomTypes(event) {
      $('.js-data-variations-custom-ajax').select2({
        ajax: {
          url: event.currentTarget.getAttribute('data-custom-route'),
          dataType: 'json',
          delay: 100,
          processResults: function (data, params) {
            var results = $.map(data, function (obj) {
              return {
                id: obj.id,
                text: obj.text
              };
            });
            return {
              results: results,
            };
          },
          cache: true
        },
        placeholder: 'Изберете разновидност ',
        // minimumInputLength: 2
      });
    },
    destroyStreet: function () {
      if ($('select#street').hasClass("form-select")) {
        this.street = null;
        $('select#street').select2('destroy');
        $('select#street').val(null).trigger('change');
      }
    },
    destroyOffice: function () {
      if ($('select#office').data('select2')) {
        this.office = null;
        this.selected_office = null;
        this.office_code = null;
        this.post_code = null;
        $('select#office').select2('destroy');
        $('select#office').val(null).trigger('change');
      }
    },
    destroyCourier: function () {
      if ($('select#courier').data('select2')) {
        this.courier = null;
        $('select#courier').select2('destroy');
        $('select#courier').val(null).trigger('change');
        this.destroyCitySelect();
        // this.destroyStreet();
      }
    },
    destroyCitySelect: function () {
      var curr = this;
      this.$nextTick(() => {
        if ($('select#city').data('select2')) {
          curr.selected_city = null
          curr.city_name = null
          $('#controller_city_id').val(null);
          $('select#city').select2('destroy');
          // this.destroyOffice();
          curr.street = null;
        }
      })
    },
    // getStreet: function () {
    //   var app = this;
    //   let url = null;
    //   if (this.courier == 'speedy') {
    //     url = $('select#street').data('ajax-speedy')
    //   } else {
    //     url = $('select#street').data('ajax-econt')
    //   }

    //   console.log(url)
    //   $('select#street').select2({
    //     ajax: {
    //       url: url,
    //       type: "POST",
    //       delay: 250,
    //       data: function (params) {
    //         return {
    //           name: params.term,
    //           siteId: app.selected_city
    //         }
    //       },
    //       processResults: function (response) {
    //         return {
    //           results: response.data,
    //         };
    //       }
    //     },
    //     templateResult: function (data) {
    //       // Използваме text свойството като текст в списъка
    //       return data.text;
    //     },
    //     templateSelection: function (data, container) {
    //       // Използваме text свойството като избран текст
    //       return data.text;
    //     },
    //   })
    //   $('select#street').on('select2:select', function (e) {
    //     const text = e.params.data.text
    //     console.log(text);
    //     app.street = text;
    //     //getDeliveryPrice();
    //   });
    // },
    getCourierCities: function () {
      if ($('#controller_city_id').val()) {
        this.selected_city = parseInt($('#controller_city_id').val());
      }
      let url = null;
      if (this.courier == 'speedy') {
        url = '/speedy/cities';
      }
      else if (this.courier == 'econt') {
        url = '/econt/cities';
      }
      var app = this;
      this.$nextTick(() => {
        $('select#city').select2({
          allowClear: true,
          placeholder: 'Изберете град',
          // language: "bg",
          minimumInputLength: 2,
          ajax: {
            url: url,
            type: "POST",
            delay: 250,
            processResults: function (response) {
              return {
                results: response.data
              };
            }
          }
        });

        $('select#city').on('select2:select', (e) => {
          app.selected_city = e.params.data.id;
          app.city_name = e.params.data.text;
          app.post_code = e.params.data.post_code;
        });

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
      })
    },

    getOffice: function () {
      let app = this;
      let url = null;
      if (this.courier == "speedy") {
        url = '/speedy/offices';
      }
      else if (this.courier == "econt") {
        url = '/econt/offices';
      }
      this.$nextTick(() => {
        $('select#office').select2({
          allowClear: true,
          placeholder: 'Избери офис',
          language: "bg",
          ajax: {
            url: url,
            type: "POST",
            data: function (params) {
              return {
                q: params.term,
                city: app.city_name,
                city_id: app.selected_city,
              }
            },
            processResults: function (response) {
              return {
                results: response.data
              };
            },
            delay: 500
          },
        });

        $('select#office').on('select2:select', function (e) {
          let data = e.params.data
          $('#office-text').val(data.text)
          app.selected_office = data.text
          app.office = data.id
          if (data.code) {
            app.office_code = data.code;
          }
        })
      })
    },

    // handleDynamicButtonClick(event) {
    //   if (event.target.classList.contains('remove_item')) {
    //     this.removeItem(event);
    //   }
    //   else if (event.target.classList.contains('add_item')) {

    //     this.addItemBtn(event);
    //   }
    // },

    initData() {
      this.payment = $('#controller_payment_method').val();
      this.delivery_method = $('#controller_delivery_method').val();
      this.admin_discount = $('#controller_admin_discount').val() ?? 0;
      if ($('#controller_free_delivery').val() == '0') {
        this.free_delivery = 0;
      }
      let app = this
      this.$nextTick(() => {
        if (app.delivery_method == 'office' || app.delivery_method == 'address') {
          app.courier = $('#controller_courier').val();
          app.selected_city = parseInt($('#controller_city_id').val());
          app.city_name = $('#controller_city_name').val();
          this.post_code = $('#controller_post_code').val();
          app.street = $('#controller_address').val();
          if (app.delivery_method == 'office') {
            app.office = parseInt($('#controller_office_id').val());
            this.selected_office = $('#controller_address').val();
          } else {
            console.log(app.street);
          }
        }
        $('#office_name').val(this.selected_office);
      })
    },

    storeDiscount() {
      this.$nextTick(() => {
        if (this.admin_discount_type == 1) {
          this.admin_discount = (this.admin_discount_value * this.final_price) / 100
        }
        else if (this.admin_discount_type == 2) {
          this.admin_discount = this.admin_discount_value
        }
        this.restoreFinalPrice();
      })

    },
    restoreFinalPrice() {
      this.final_price = this.total_price;
      this.calculateDeliveryPrice();
      if (this.free_delivery == false && this.delivery_price != null) {
        this.final_price = parseFloat(this.final_price) + this.delivery_price;
      }
      this.final_price -= parseFloat(this.admin_discount);
    },
    removeAdminDiscount() {
      this.$nextTick(() => {
        this.admin_discount = 0;
        this.restoreFinalPrice();
      });
    }
  },

  mounted: function () {
    this.order_number = $('#order_number').val();
    this.order_items = this.getOrderItems();
    this.datatable = this.initDatatable();
    $(document).on('click', '.remove_item', this.removeItem);
    $(document).on('click', '.add_item', this.addItemBtn);
    $(document).on('change', '.input_quantity', this.updateItemQuantity);
    $(document).on('click', '#submit_variation', this.submitItemWithVariations);
    this.initData();
    this.delivery_price = $('#controller_delivery_price').val();

  },
});
