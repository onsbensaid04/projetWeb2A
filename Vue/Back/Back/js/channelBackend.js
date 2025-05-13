// channel-management.js

$(document).ready(function() {
    // Load all channels on page load
    loadChannels();

    // Setup event delegation for double-click editing
    $('#channelTableBody').on('dblclick', 'td.editable', function() {
        const channelId = $(this).closest('tr').data('id'); // FIXED
        openEditModal(channelId);
    });

    // Form submission handling with AJAX for edit form
    $('#editChannelForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#editChannelModal').modal('hide');
                loadChannels(); // Reload the table
                alert('Channel updated successfully!');
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText);
                alert('Error updating channel: ' + error);
            }
        });
    });

    // Form submission handling with AJAX for delete form
    $('#deleteChannelForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#deleteChannelModal').modal('hide');
                loadChannels(); // Reload the table
                alert('Channel deleted successfully!');
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText);
                alert('Error deleting channel: ' + error);
            }
        });
    });

    // Menu functionality
    var scrollLink = $('.scroll-to-section');

    // Smooth scrolling
    scrollLink.click(function(e) {
        e.preventDefault();
        $('body,html').animate({
            scrollTop: $(this.hash).offset().top
        }, 1000);
    });

    // Active link switching
    $(window).scroll(function() {
        var scrollbarLocation = $(this).scrollTop();

        scrollLink.each(function() {
            var sectionOffset = $(this.hash).offset().top - 20;

            if (sectionOffset <= scrollbarLocation) {
                $(this).parent().addClass('active');
                $(this).parent().siblings().removeClass('active');
            }
        });
    });

    // Menu dropdown
    $('.has-sub').on('click', function() {
        $(this).toggleClass('active');
        $(this).find('.sub-menu').slideToggle(200);
    });

    // Fixed header
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var header = $('.header-area');

        if (scroll >= 100) {
            header.addClass('header-sticky');
        } else {
            header.removeClass('header-sticky');
        }
    });
});

// Function to load all channels from the API
function loadChannels() {
    $.ajax({
        url: '/integration/Controller/getchannels.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                $('#channelTableBody').html('<tr><td colspan="5" class="text-center text-danger">Error: ' + data.error + '</td></tr>');
                return;
            }

            if (data.length === 0) {
                $('#channelTableBody').html('<tr><td colspan="5" class="text-center">No channels found. Create your first channel!</td></tr>');
                return;
            }

            let tableContent = '';
            data.forEach(function(channel) {
                tableContent += `
                    <tr data-id="${channel.id}">
                        <td>${channel.id}</td>
                        <td><img src="${channel.image_url || '/integration/uploads/logo.png.png'}" alt="${channel.name}" class="channel-image"></td>
                        <td class="editable">${channel.name}</td>
                        <td class="editable">${channel.description}</td>
                        <td class="actions-column">
                            <button type="button" class="btn btn-sm btn-primary btn-actions" onclick="openEditModal(${channel.id})">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-actions" onclick="openDeleteModal(${channel.id}, '${channel.name}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#channelTableBody').html(tableContent);
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', xhr.responseText);
            $('#channelTableBody').html('<tr><td colspan="5" class="text-center text-danger">Error loading channels: ' + error + '</td></tr>');
        }
    });
}

// Function to open the edit modal and populate with channel data
function openEditModal(channelId) {
    $.ajax({
        url: '/integration/Controller/getchannels.php?id=' + channelId,

        type: 'GET',
        dataType: 'json',
        success: function(channel) {
            if (channel.error) {
                alert('Error: ' + channel.error);
                return;
            }

            $('#edit-channel-id').val(channel.id);
            $('#edit-channel-name').val(channel.name);
            $('#edit-channel-description').val(channel.description);

            if (channel.image_url) {
                $('#currentImage').attr('src', channel.image_url);
                $('#currentImageContainer').show();
            } else {
                $('#currentImageContainer').hide();
            }

            $('#edit-channel-image').val('');
            $('#editImagePreview').hide();

            $('#editChannelModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', xhr.responseText);
            alert('Error fetching channel details: ' + error);
        }
    });

}

// Function to open the delete confirmation modal
function openDeleteModal(channelId, channelName) {
    $('#delete-channel-id').val(channelId);
    $('#deleteChannelName').text(channelName);
    $('#deleteChannelModal').modal('show');
}

// Image preview for edit form
function previewEditImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('editImagePreview');
        output.src = reader.result;
        output.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
