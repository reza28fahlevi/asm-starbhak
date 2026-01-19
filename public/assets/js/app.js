/**
 * PKM ASM - Application JavaScript
 * Contains common AJAX functions and utilities
 */

// CSRF Token Helper
function getCsrfToken() {
  return $('meta[name="csrf-token"]').attr('content');
}

function getCsrfName() {
  return $('meta[name="csrf-name"]').attr('content');
}

// Setup AJAX to include CSRF token
$.ajaxSetup({
  headers: {
    'X-Requested-With': 'XMLHttpRequest'
  }
});

/**
 * User Management Functions
 */
const UserManager = {
  
  /**
   * Delete user with confirmation
   */
  delete: function(userId, username, redirectUrl) {
    Swal.fire({
      title: 'Konfirmasi Hapus',
      html: `Apakah Anda yakin ingin menghapus user <strong>${username}</strong>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      showLoaderOnConfirm: true,
      preConfirm: () => {
        return $.ajax({
          url: redirectUrl + '/user/delete/' + userId,
          type: 'POST',
          data: {
            [getCsrfName()]: getCsrfToken()
          }
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed) {
        toastr.success('User berhasil dihapus');
        setTimeout(() => location.reload(), 1000);
      }
    }).catch((error) => {
      toastr.error('Gagal menghapus user');
    });
  },

  /**
   * Toggle user active status
   */
  toggleActive: function(userId, baseUrl) {
    $.ajax({
      url: baseUrl + '/user/toggleActive/' + userId,
      type: 'POST',
      data: {
        [getCsrfName()]: getCsrfToken()
      },
      beforeSend: function() {
        toastr.info('Memproses...');
      },
      success: function(response) {
        if (response.success) {
          toastr.success(response.message || 'Status berhasil diubah');
          setTimeout(() => location.reload(), 1000);
        } else {
          toastr.error(response.message || 'Gagal mengubah status');
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Terjadi kesalahan pada server');
      }
    });
  },

  /**
   * Load user data via AJAX
   */
  loadData: function(baseUrl, callback) {
    $.ajax({
      url: baseUrl + '/user/getData',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        if (callback && typeof callback === 'function') {
          callback(response);
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Gagal memuat data user');
      }
    });
  },

  /**
   * Create/Update user via AJAX
   */
  save: function(formData, baseUrl, isEdit = false) {
    const url = isEdit 
      ? baseUrl + '/user/update/' + formData.get('id')
      : baseUrl + '/user/store';

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        toastr.info('Menyimpan data...');
      },
      success: function(response) {
        if (response.success) {
          toastr.success(response.message || 'Data berhasil disimpan');
          setTimeout(() => {
            window.location.href = baseUrl + '/user';
          }, 1500);
        } else {
          toastr.error(response.message || 'Gagal menyimpan data');
          
          // Show validation errors
          if (response.errors) {
            $.each(response.errors, function(field, message) {
              toastr.error(message);
            });
          }
        }
      },
      error: function(xhr, status, error) {
        toastr.error('Terjadi kesalahan pada server');
      }
    });
  }
};

/**
 * DataTable Helper
 */
const DataTableHelper = {
  
  /**
   * Initialize DataTable with default Indonesian language
   */
  init: function(selector, options = {}) {
    const defaultOptions = {
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
      },
      pageLength: 10,
      ordering: true,
      searching: true,
      responsive: true,
      processing: true,
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    };

    return $(selector).DataTable($.extend({}, defaultOptions, options));
  },

  /**
   * Reload DataTable
   */
  reload: function(table) {
    table.ajax.reload(null, false);
  }
};

/**
 * Form Helper
 */
const FormHelper = {
  
  /**
   * Serialize form to FormData
   */
  toFormData: function(formSelector) {
    const formData = new FormData($(formSelector)[0]);
    return formData;
  },

  /**
   * Reset form and clear validation
   */
  reset: function(formSelector) {
    $(formSelector)[0].reset();
    $(formSelector).find('.is-invalid').removeClass('is-invalid');
    $(formSelector).find('.invalid-feedback').remove();
  },

  /**
   * Show validation errors
   */
  showErrors: function(formSelector, errors) {
    // Clear previous errors
    $(formSelector).find('.is-invalid').removeClass('is-invalid');
    $(formSelector).find('.invalid-feedback').remove();

    // Show new errors
    $.each(errors, function(field, message) {
      const input = $(formSelector).find('[name="' + field + '"]');
      input.addClass('is-invalid');
      input.after('<div class="invalid-feedback">' + message + '</div>');
    });
  }
};

/**
 * Notification Helper
 */
const NotificationHelper = {
  
  success: function(message) {
    toastr.success(message);
  },

  error: function(message) {
    toastr.error(message);
  },

  warning: function(message) {
    toastr.warning(message);
  },

  info: function(message) {
    toastr.info(message);
  },

  /**
   * Confirm dialog
   */
  confirm: function(title, message, callback) {
    Swal.fire({
      title: title,
      html: message,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed && callback) {
        callback();
      }
    });
  },

  /**
   * Confirm delete
   */
  confirmDelete: function(message, callback) {
    Swal.fire({
      title: 'Konfirmasi Hapus',
      html: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed && callback) {
        callback();
      }
    });
  }
};

/**
 * Document Ready
 */
$(document).ready(function() {
  
  // Auto-hide alerts after 5 seconds
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 5000);

  // Confirm links with data-confirm attribute
  $('[data-confirm]').on('click', function(e) {
    e.preventDefault();
    const message = $(this).data('confirm');
    const href = $(this).attr('href');
    
    NotificationHelper.confirm('Konfirmasi', message, function() {
      window.location.href = href;
    });
  });

  // Submit buttons with loading state
  $('form').on('submit', function() {
    const submitBtn = $(this).find('[type="submit"]');
    submitBtn.prop('disabled', true);
    submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');
  });
});
