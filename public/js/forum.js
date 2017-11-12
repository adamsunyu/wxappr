
if (typeof String.prototype.trim === "undefined") {
    String.prototype.trim = function() {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}

/**
 * Forum
 */
var Forum = {

	_uri: '',

	_search: false,

	/**
	 * Transform a comment into a editable box
	 */
	makeCommentEditable: function(response)
	{
		if (response.status == 'OK') {

			var form = document.createElement('FORM');
			form.className = 'edit-form';
			form.method = 'POST';
			form.action = Forum._uri + 'reply/update';

			var textarea = document.createElement('TEXTAREA');
			textarea.name = 'content';
			textarea.rows = 7;
			textarea.value = response.comment;
			textarea.className = 'form-control';
			form.appendChild(textarea);

			var hidden = document.createElement('INPUT');
			hidden.name = 'id';
			hidden.type = 'hidden';
			hidden.value = response.id;
			form.appendChild(hidden);

			var token = document.createElement('INPUT');
			token.name = $('#csrf-token').attr('name');
			token.type = 'hidden';
			token.value = $('#csrf-token').attr('value');
			form.appendChild(token);

            var submit = document.createElement('INPUT');
			submit.type = 'button';
			submit.className = 'btn btn-success btn-sm pull-right';
            submit.style = 'margin-left:10px;';
			submit.value = '修改';
			$(submit).bind('click', { form: form }, function(event) {
				this.disabled = true;
				event.data.form.submit();
			});

			var cancel = document.createElement('INPUT');
			cancel.type = 'button';
			cancel.className = 'btn btn-default btn-sm pull-right';
			cancel.value = '取消';
			$(cancel).bind('click', { form: form, element: this}, Forum.cancelEditing);

            var buttonDiv = document.createElement('div');
            buttonDiv.className = "btn-box-edit clearfix";
            buttonDiv.appendChild(submit);
            buttonDiv.appendChild(cancel);

            form.appendChild(buttonDiv);

			this.hide();

			this.parent().append(form);
		}
	},

	/**
	 * Shows the reply box
	 */
	addBaseComment: function(response)
	{
		if (response.status == 'OK') {

			var parts = response.comment.split(/\r\n|\r|\n/), str = "";
			for (var i = 0; i < parts.length; i++) {

                if (parts[i].length >= 1 && parts[i].substring(0, 1) == '>') {
                    continue;
                }

                if (str == "" && parts[i].length == 0) {
                    continue;
                }
				str += ">" + parts[i] + "\r\n";
			}

            str += '\r\n';

			$('#replyModal').modal('show');
            $('#replyModal #comment-textarea').html('<textarea name="content" id="replyContent"></textarea>');
			var textarea = $('#replyModal textarea')[0];
            $(textarea).val(str);

            $('#replyModal').on('shown.bs.modal', function () {
                $('textarea').focus();
                $(textarea).val('').val(str);
            });
		}
	},

	/**
	 * Cancels the comment editing
	 */
	cancelEditing: function(event)
	{
		//Are you sure you want to delete this?
		var element = $(event.data.element);
		var form = $(event.data.form);
		$('div.posts-bar', element.parents()[1]).show();
		element.show();
		form.remove();
	},

	/**
	 * Deletes a comment
	 */
	deleteReply: function(event)
	{
		if (confirm('确定删除这条回复？')) {
			var element = $(event.data.element);
			window.location = Forum._uri + 'reply/delete/' + element.data('id') + '?' + $('#csrf-token').attr('name') + '=' + $('#csrf-token').attr('value');
		}
	},

    /**
     * Deletes a comment
     */
    deletePost: function(event)
    {
        if (confirm('确定删除这条主题？')) {
            var element = $(event.data.element);
            window.location = Forum._uri + 'delete/post/' + element.data('id') + '?' + $('#csrf-token').attr('name') + '=' + $('#csrf-token').attr('value');
        }
    },

	/**
	 * Converts the post-comment div into an editable textarea
	 */
	editComment: function(event)
	{
		var element = $(event.data.element);
		if (element.length) {
			var content = $('div.post-content', element.parents()[2]);
			$('div.posts-bar', element.parents()[2]).hide();
			if (content.is(':visible')) {
				$.ajax({
					dataType: 'json',
					url: Forum._uri + 'reply/' + element.data('id'),
					context: content
				}).done(Forum.makeCommentEditable);
			}
		} else {
			alert('Cannot trigger event');
		}
	},

	/**
	 * Converts the post-comment div into an editable textarea
	 */
	replyReply: function(event)
	{
		var element = $(event.data.element);
		if (element.length) {
			$('#reply-id').val(element.data('id'))
			$.ajax({
				dataType: 'json',
				url: Forum._uri + 'reply/' + element.data('id')
			}).done(Forum.addBaseComment);
		} else {
			alert('Cannot trigger event');
		}
	},

	/**
	 * Vote a post
	 */
	votePost: function(event)
	{

		var element = $(event.data.element);

		if (element.length) {
			var csrf = {}
			csrf[$('#csrf-token').attr('name')] = $('#csrf-token').attr('value')

            var postId = element.data('id');
            var voteType = element.data('type');

			$.ajax({
				dataType: 'json',
				url: Forum._uri + 'post/vote/' + postId + '/' + voteType,
				data: csrf
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
			});
		} else {
			alert('Cannot trigger event');
		}
	},

	voteApp: function(event)
	{
		var element = $(event.data.element);

		if (element.length) {
			var csrf = {}
			csrf[$('#csrf-token').attr('name')] = $('#csrf-token').attr('value');

            var appId = element.data('id');
            var voteType = element.data('type');

			$.ajax({
				dataType: 'json',
				url: Forum._uri + 'app/vote/' + appId + '/' + voteType,
				data: csrf
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
                console.log(response);
			});
		} else {
			alert('Cannot trigger event');
		}
	},

	createFormGroup: function()
	{
		var formGroup  = document.createElement('DIV');
		formGroup.className = 'form-group';
		formGroup.style = "min-height: 34px";

		return formGroup;
	},

	/**
	 * Vote a post up
	 */
	voteReplyUp: function(event)
	{
		var element = $(event.data.element);
		if (element.length) {
			var csrf = {}
			csrf[$('#csrf-token').attr('name')] = $('#csrf-token').attr('value')
			$.ajax({
				dataType: 'json',
				url: Forum._uri + 'reply/vote-up/' + element.data('id'),
				data: csrf
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
			});
		} else {
			alert('Cannot trigger event');
		}
	},

    /**
     * Follow a node
     */
    followNode: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {
            var csrf = {}
            $.ajax({
                dataType: 'json',
                url: Forum._uri + 'node-follow/' + element.data('id')
            }).done(function(response){
                if (response.status == "error") {
                    $('#errorModal .modal-body').html(response.message);
                    $('#errorModal').modal('show');
                } else {
                    window.location.reload(true);
                }
            });
        } else {
            alert('Cannot trigger event');
        }
    },

    unfollowNode: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {
            var csrf = {}
            csrf[$('#csrf-token').attr('name')] = $('#csrf-token').attr('value')
            $.ajax({
                dataType: 'json',
                url: Forum._uri + 'node-unfollow/' + element.data('id')
            }).done(function(response){
                if (response.status == "error") {
                    $('#errorModal .modal-body').html(response.message);
                    $('#errorModal').modal('show');
                } else {
                    window.location.reload(true);
                }
            });
        } else {
            alert('Cannot trigger event');
        }
    },

    showUnfollowButton: function(event)
    {
        var element = $(event.data.element);

        $(element).addClass('btn-default');
        $(element).removeClass('btn-success');
        $(element).html('取消关注');
    },

    hideUnfollowButton: function(event)
    {
        var element = $(event.data.element);
        $(element).addClass('btn-success');
        $(element).removeClass('btn-default');
        $(element).html('<span class="glyphicon glyphicon-star"></span>&nbsp;已关注');
    },

    /**
     * Follow a user
     */
    followUser: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {
            $.ajax({
                dataType: 'json',
                url: Forum._uri + 'user-follow/' + element.data('id')
            }).done(function(response){
                if (response.status == "error") {
                    $('#errorModal .modal-body').html(response.message);
                    $('#errorModal').modal('show');
                } else {
                    window.location.reload(true);
                }
                console.log(response);
            });
        } else {
            alert('Cannot trigger event');
        }
    },

    unfollowUser: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {
            $.ajax({
                dataType: 'json',
                url: Forum._uri + 'user-unfollow/' + element.data('id')
            }).done(function(response){
                if (response.status == "error") {
                    $('#errorModal .modal-body').html(response.message);
                    $('#errorModal').modal('show');
                } else {
                    window.location.reload(true);
                }
            });
        } else {
            alert('Cannot trigger event');
        }
    },

    showUnfollowUserButton: function(event)
    {
        var element = $(event.data.element);

        $(element).addClass('btn-default');
        $(element).removeClass('btn-success');
        $(element).html('取消关注');
    },

    hideUnfollowUserButton: function(event)
    {
        var element = $(event.data.element);
        $(element).addClass('btn-success');
        $(element).removeClass('btn-default');
        $(element).html('<span class="glyphicon glyphicon-star"></span>&nbsp;已关注');
    },

	/**
	 * Vote a reply down
	 */
	voteReplyDown: function(event)
	{
		var element = $(event.data.element);
		if (element.length) {
			var csrf = {}
			csrf[$('#csrf-token').attr('name')] = $('#csrf-token').attr('value')
			$.ajax({
				dataType: 'json',
				url: Forum._uri + 'reply/vote-down/' + element.data('id'),
				data: csrf
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
			});
		} else {
			alert('Cannot trigger event');
		}
	},

	/**
	 * Vote a post up
	 */
	needLogin: function(event)
	{
		window.location = Forum._uri + 'account/login';
	},

    openLink: function(event)
    {
        event.preventDefault();
        var element = $(event.data.element);
        url = $(this).attr('href');
        if (url != null) {
            window.open(url, '_blank');
        }
    },

    uploadAvatar: function(event)
    {
        var file = this.files[0];

        if (file.size > 512000) {

            $('#errorModal .modal-body').html('图片太大，请上传500K以内的图片');
            $('#errorModal').modal('show');
            return;
        }

        var fd = new FormData();

        fd.append('avatar', file);

        $.ajax({
            url: "/account/upload",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
        }).done(function(response){
            if (response.status == "error") {
                $('#errorModal .modal-body').html(response.message);
                $('#errorModal').modal('show');
            } else {
                setTimeout(function() {
                    window.location.reload(true);
                }, 1000);
            }
        });;
    },

    editSocial: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {

            $id = element.data('id');

            $('#setting-social-' + $id).hide();
            $('#setting-form-' + $id).show();
            $('#setting-btn-' + $id).show();

            $val = $('#setting-social-' + $id).text();

            if ($val != '未知') {

                if ($id == 'a') {

                    $gender = 'O';
                    if ($val == '男') {
                        $gender = 'M';
                    } else if ($val == '女') {
                        $gender = 'W';
                    }
                    $('#setting-value-' + $id).val($gender);

                } else {
                    $('#setting-value-' + $id).val($val);
                }
            }

            $('#setting-value-' + $id).focus();

            element.hide();
        } else {
            alert('Cannot trigger event');
        }
    },

    saveEditSocial: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {

            var id = element.parent().data('id');
            var value = $("#setting-value-"+$id).val();

            $.ajax({
                method: 'POST',
                url: Forum._uri + 'account/social-update',
                data: {'id': id, 'content': value }
            }).done(function(response){
                if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
            });
        } else {
            alert('Cannot trigger event');
        }
    },

    changeNoteStatus: function(event)
    {
        var element = $(event.data.element);

        if (this.value == 'true') {
            $("#nodeSelectorBox").show();
            $("#nodeSelector").attr('required', 'required');
        } else {
            $("#nodeSelector").removeAttr('required');
            $("#nodeSelectorBox").hide();
        }
    },

    cancelComment: function(event)
    {
        var element = $(event.data.element);
        $('.btn-box-edit').hide();
    },

	showThankPopup: function(event)
	{
		var element = $(event.data.element);
		if (element.length) {
            var itemType = element.data('type');

            if(itemType == 'S') {
                $('#errorModal #errorBody').html('<h3>这是你自己</h3>');
                $('#errorModal').modal('show');
                return;
            }

            $('#thankUserId').val(element.data('userid'));
            $('#thankType').val(itemType);

            var itemTypeStr = '';

            if (itemType == 'Q') {
                itemTypeStr = '问题';
            } else if(itemType == 'P') {
                itemTypeStr = '话题';
            } else if(itemType == 'R') {
                itemTypeStr = '回复';
                $('#itemSubId').val(element.data('id'));
            } else if(itemType == 'A') {
                itemTypeStr = '回答';
                $('#itemSubId').val(element.data('id'));
            }

            $('#thankModalLabel').html('感谢' + element.data('name')+'的'+itemTypeStr);
            $('#thankModal').modal('show');
		} else {
			alert('Cannot trigger event');
		}
	},

    thankAmountMinus: function(event)
    {
        var limit = parseInt($("#thankLimit").val())
        var amount = $("#thankAmount").val();

        if (amount >= 20) {
            amount -= 10;
        } else if(amount > 10) {
            amount -= 1;
        }

        $("#thankAmount").val(amount);

        if (amount <= 10) {
            $("#thankLimitError").html("(预算奖金不能少于10微币)");
            $("#thankLimitError").show();
        } else if (amount < limit) {
            $("#thankLimitError").hide();
        }
    },

    thankAmountPlus: function(event)
    {
        var limit = parseInt($("#thankLimit").val())
        var amount = parseInt($("#thankAmount").val());

        amount += 10;

        if (amount > limit) {
            $("#thankLimitError").html("(预算奖金不能超过你的总资产: "+limit+"微币)");
            $("#thankLimitError").show();
        } else {
            $("#thankLimitError").hide();

            $("#thankAmount").val(amount);
            $("#thankAmount").blur();
        }
    },

    thankAmountEdit: function(event)
    {
        var limit = parseInt($("#thankLimit").val())
        var amount = parseInt($("#thankAmount").val());

        if(isNaN(amount)) {
            $("#thankLimitError").html("(不合格的数字)");
            $("#askModel input[type=submit]").attr("disabled", "disabled");
            $("#thankLimitError").show();
        } else if (amount <= 10 ) {
            $("#thankLimitError").html("(预算奖金不能少于10微币)");
            $("#askModel input[type=submit]").attr("disabled", "disabled");
            $("#thankLimitError").show();
        } else if(amount > limit) {
            $("#thankLimitError").html("(预算奖金不能超过你的总资产: "+limit+"微币)");
            $("#askModel input[type=submit]").attr("disabled", "disabled");
            $("#thankLimitError").show();
        } else {
            $("#thankLimitError").hide();
            $("#askModel input[type=submit]").removeAttr("disabled");
            $("#thankAmount").val(amount);
        }
    },

    selectThankOption: function(event)
    {
        var element = $(event.data.element);
		if (element.length) {

            var optionId = element.data('id');

            if (optionId == 1) {
                $("#thankAmount").val(5);
            } else if(optionId == 2) {
                $("#thankAmount").val(10);
            } else if(optionId == 3) {
                $("#thankAmount").val(20);
            }

            $('.btn-thank-option').removeClass('btn-warning');
            $('.btn-thank-option').addClass('btn-default');
            element.attr('class', 'btn btn-warning btn-thank-option');
            element.blur();
        }
    },

    submitThank: function(event)
	{
		var element = $(event.data.element);

		if (element.length) {

            var thankType = $("#thankType").val();
            var amount = parseInt($("#thankAmount").val());
            var thankUserId = $("#thankUserId").val();
            var mainId = $("#itemMainId").val();
            var subId = $("#itemSubId").val();

            $.ajax({
                method: 'POST',
				dataType: 'json',
				url: Forum._uri + 'thankUser' + '?' + $('#csrf-token').attr('name') + '=' + $('#csrf-token').attr('value'),
				data: {'toUserId': thankUserId, 'mainId': mainId, 'subId': subId, 'thankType': thankType, 'amount': amount}
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
			});
		} else {
			alert('Cannot trigger event');
		}
	},

    selectStickOption: function(event)
    {
        var element = $(event.data.element);
		if (element.length) {

            var optionId = element.data('id');

            if (optionId == 1) {
                $("#stickAmount").val(24);
                $("#stickModelTip").html("花费: " + 24 + "微币");
            } else if(optionId == 2) {
                $("#stickAmount").val(48);
                $("#stickModelTip").html("花费: " + 48 + "微币");
            } else if(optionId == 3) {
                $("#stickAmount").val(72);
                $("#stickModelTip").html("花费: " + 72 + "微币");
            }

            $('.btn-stick-option').removeClass('btn-warning');
            $('.btn-stick-option').addClass('btn-default');

            element.attr('class', 'btn btn-warning btn-middle btn-stick-option');
            element.blur();
        }
    },

    showStickPopup: function(event)
	{
		var element = $(event.data.element);
		if (element.length) {
            var itemType = element.data('type');
            $('#stickModal').modal('show');
		} else {
			alert('Cannot trigger event');
		}
	},

    submitStick: function(event)
	{
		var element = $(event.data.element);

		if (element.length) {

            var amount = parseInt($("#stickAmount").val());
            var itemId = $("#itemId").val();

            $.ajax({
                method: 'POST',
				dataType: 'json',
				url: Forum._uri + 'stick/post/' + itemId,
				data: {'stickAmount': amount}
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
			});
		} else {
			alert('Cannot trigger event');
		}
	},

    showSendMessage: function(event)
    {
        var element = $(event.data.element);

        if (element.length) {

            var userLogin = element.data('login');
            var userName = element.data('name');

            $('#toUserId').val(element.data('id'));

            $('#userModalLabel').html('发私信给 <a href="/user/'+userLogin+'">' + userName + '</a>');

            $('#sendMessageModal').modal('show');

        } else {
            alert('Cannot trigger event');
        }
    },

    submitMessage: function(event)
	{
		var element = $(event.data.element);

		if (element.length) {

            var message = $("#messageArea").val();
            var toUserId = $("#toUserId").val();

            $.ajax({
                method: 'POST',
				dataType: 'json',
				url: Forum._uri + 'message/send' + '?' + $('#csrf-token').attr('name') + '=' + $('#csrf-token').attr('value'),
				data: {'toUserId': toUserId, 'message': message}
			}).done(function(response){
				if (response.status == "error") {
					$('#errorModal .modal-body').html(response.message);
					$('#errorModal').modal('show');
				} else {
					window.location.reload(true);
				}
			});
		} else {
			alert('Cannot trigger event');
		}
	},

    messageEdit: function(event)
    {
        var message = $("#messageArea").val();
        if (message.length > 0) {
            $("#btn-submit-message").removeAttr("disabled");
        } else {
            $("#btn-submit-message").attr("disabled", "disabled");
        }
    },

    deleteMessage: function(event)
	{
		if (confirm('确定删除这条私信？')) {
			var element = $(event.data.element);
            var msgId = element.data('id');
            var msgType = element.data('type');
			window.location = Forum._uri + 'message/delete/' + msgId + '?' + 'type' + '=' + msgType;
		}
	},

    selectApptagOption: function(event)
    {
        var element = $(event.data.element);

		if (element.length) {

            var optionId = element.data('id');

            var elementVal = $(element).val();

            $("#appTags").tagsinput('add', elementVal);

            element.blur();
        }
    },

    fetchLinkTitle: function(event)
	{

		var element = $(event.data.element);

		if (element.length) {

            var linkURL = $("#link").val();

            if (linkURL.length > 0) {
                $("#btn-fetch-link-loading").show();
                $.ajax({
                    dataType: 'json',
                    url: Forum._uri + 'fetch/link',
                    data: {'linkURL': linkURL}
                }).done(function(response){
                    $("#btn-fetch-link-loading").hide();
                    console.log(response);
                    if (response.status == "error") {
                        $('#errorModal .modal-body').html(response.message);
                        $('#errorModal').modal('show');
                    } else {
                        var linkTitle = response.title;
                        $("#title").val(linkTitle);
                    }
                });
            }
		} else {
			alert('Cannot trigger event');
		}
	},

	/**
	 * Add callbacks to edit/delete buttons
	 */
	addCallbacks: function()
	{
        $('.btn-node-follow').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.followNode);
		});

        $('.btn-node-unfollow').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.unfollowNode);
		});

        $('.btn-node-unfollow').each(function(position, element) {
			$(element).bind('mouseenter', {element: element}, Forum.showUnfollowButton);
		});

        $('.btn-node-unfollow').each(function(position, element) {
			$(element).bind('mouseleave', {element: element}, Forum.hideUnfollowButton);
		});

        $('.btn-user-follow').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.followUser);
		});

        $('.btn-user-unfollow').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.unfollowUser);
		});

        $('.btn-user-unfollow').each(function(position, element) {
			$(element).bind('mouseenter', {element: element}, Forum.showUnfollowUserButton);
		});

        $('.btn-user-unfollow').each(function(position, element) {
			$(element).bind('mouseleave', {element: element}, Forum.hideUnfollowUserButton);
		});

		$('a.btn-reply-edit').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.editComment);
		});

		$('a.btn-reply-remove').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.deleteReply);
		});

        $('a.btn-delete-post').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.deletePost);
		});

		$('span.action-edit').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.postHistory);
		});

		$('span.action-reply-edit').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.replyHistory);
		});

		$('a.btn-vote-post').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.votePost);
		});

        $('a.btn-vote-app').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.voteApp);
		});

		$('a.vote-reply-up').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.voteReplyUp);
		});

		$('a.vote-reply-down').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.voteReplyDown);
		});

		$('a.btn-reply-reply').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.replyReply);
		});

		$('a.need-login').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.needLogin);
		});

        $('.post-content a').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.openLink);
		});

        $('#input-upload-avatar').on('change', Forum.uploadAvatar);

        $('a.setting-social-edit').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.editSocial);
		});

        $('.setting-social-btn').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.saveEditSocial);
		});

        $('.note-checkbox').each(function(position, element) {
			$(element).bind('change', {element: element}, Forum.changeNoteStatus);
		});

        $('.btn-cancel-comment').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.cancelComment);
		});

        $('.thank-user').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.showThankPopup);
		});

        $('.btn-stick-option').each(function(position, element) {
            $(element).bind('click', {element: element}, Forum.selectStickOption);
        });

        $('.btn-stick-post').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.showStickPopup);
		});

        $('#btn-submit-stick').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.submitStick);
		});

        $('.btn-send-message').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.showSendMessage);
		});

        $('.btn-thank-option').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.selectThankOption);
		});

        $('#btn-submit-thank').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.submitThank);
		});

        $('#btn-submit-message').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.submitMessage);
		});

        $('#btn-amount-minus').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.thankAmountMinus);
		});

        $('#btn-amount-plus').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.thankAmountPlus);
		});

        $('#thankAmount').each(function(position, element) {
			$(element).bind('keyup', {element: element}, Forum.thankAmountEdit);
		});

        $('#messageArea').each(function(position, element) {
			$(element).bind('keyup', {element: element}, Forum.messageEdit);
		});

        $('a.btn-message-delete').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.deleteMessage);
		});

        $('.btn-apptag-option').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.selectApptagOption);
		});

        $('#btn-fetch-title').each(function(position, element) {
			$(element).bind('click', {element: element}, Forum.fetchLinkTitle);
		});
    },

    editorRender: function()
    {
        if ($("#contentArea").length) {
            var simplemde = new SimpleMDE({
                autoDownloadFontAwesome: false,
                showIcons: ["code", "table"],
                toolbar: ["bold", "italic", "heading", "|", "quote", "code", "|", "link", "image", "table", "|", "preview"],
                hideIcons: ["guide"],
                spellChecker: false,
                status: false,
                forceSync: true,
                element: document.getElementById("contentArea"),
            });
            simplemde.value($("#contentArea").val());

            var uploader = new qq.FineUploader({
                element: document.getElementById("uploader"),
                listElement: document.getElementById("upload-list"),
                debug: false,
                request: {
                    endpoint: "/image/upload"
                },
                validation: {
                    allowedExtensions: ['jpeg', 'jpg', 'png', 'gif'],
                    sizeLimit: 512000
                },
                showMessage: function(message) {
                    var theMsg = message;

                    if (theMsg.search(/an invalid extension/i) != -1) {
                        theMsg = '图片格式不正确，支持的图片格式：jpeg, jpg, png, gif';
                    } else if (theMsg.search(/maximum file size/i) != -1) {
                        theMsg = '图片太大，图片大小不能超过500KB';
                    }

                    $('#errorModal .modal-body').html(theMsg);
                    $('#errorModal').modal('show');
                },
                callbacks: {
                    onComplete: function(id, fileName, responseJSON) {
                        if (responseJSON.success) {
                            var url = '![](https://www.wxappr.com' + responseJSON['folder'] + responseJSON['uploadName'] + ')';
                            var newTxt = $("#contentArea").val() + "\n" + url;
                            simplemde.value(newTxt);
                            $(".qq-file-id-" + id + " > .wx-formatted-name").html(url);

                        } else {
                            $('#errorModal .modal-body').html(responseJSON.error);
                            $('#errorModal').modal('show');
                        }
                    }
                },
            });
        }
    },

    nodeSelectRender: function()
    {
        $('#nodeSelector').select2({
            theme: "bootstrap",
            tags: true
        });
    },

    imageViewer: function()
    {
        if ($("#post-content-box img").length) {
			new Viewer(document.getElementById('post-content-box'), {
				'inline': false,
				'navbar': false,
				'toolbar': false,
				'title': false,
				'rotatable': false,
				'transition': false,
				'movable': true
			});
		}

        if ($(".discussion .app-screenshots-list img").length) {
			new Viewer(document.getElementById('app-screenshots-list-id'), {
				'inline': false,
				'navbar': true,
				'toolbar': false,
				'title': false,
				'rotatable': false,
				'transition': false,
				'movable': true
			});
		}
    },

    textAreaResize: function()
    {
        if ($("#commentArea").length) {
            autosize($('#commentArea'));
        }
    },

    appIconUpload:function()
    {
        var appId = $("#appId").val();

        var uploader = new qq.FineUploaderBasic({
            button: document.getElementById("app-icon-upload-box"),
            debug: true,
            request: {
                endpoint: "/appimage/icon/" + appId
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'png'],
                sizeLimit: 20480000
            },
            callbacks: {
                onComplete: function(id, fileName, responseJSON) {

                    if (responseJSON.success) {

                        $("#app-icon-upload-box .iconfont").hide();
                        $("#app-icon-upload-icon-image").show();
                        $("#app-icon-upload-icon-image").attr("src", responseJSON.imageURI);

                    } else {

                        $('#errorModal .modal-body').html(responseJSON.error);
                        $('#errorModal').modal('show');
                    }
                }
            },
        });
    },

    appQrcodeUpload:function()
    {
        var appId = $("#appId").val();

        var uploader = new qq.FineUploaderBasic({
            button: document.getElementById("app-qrcode-upload-box"),
            debug: true,
            request: {
                endpoint: "/appimage/qrcode/" + appId
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'png'],
                sizeLimit: 20480000
            },
            callbacks: {
                onComplete: function(id, fileName, responseJSON) {

                    if (responseJSON.success) {

                        $("#app-qrcode-upload-box .iconfont").hide();
                        $("#app-qrcode-upload-icon-image").show();
                        $("#app-qrcode-upload-icon-image").attr("src", responseJSON.imageURI);

                    } else {

                        $('#errorModal .modal-body').html(responseJSON.error);
                        $('#errorModal').modal('show');
                    }
                }
            },
        });
    },

    appScreenshotShow:function(imgId)
    {
        var appId = $("#appId").val();

        var uploader = new qq.FineUploaderBasic({
            button: document.getElementById("app-screenshots-button-"+imgId),
            debug: true,
            request: {
                endpoint: "/appimage/screenshot/" + appId + "/" + imgId
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'png'],
                sizeLimit: 20480000
            },
            callbacks: {
                onComplete: function(id, fileName, responseJSON) {

                    if (responseJSON.success) {

                        $("#app-screenshots-button-" + imgId + " .iconfont").hide();
                        $("#app-screenshots-image-" + imgId).show();
                        $("#app-screenshots-image-" + imgId).attr("src", responseJSON.imageURI);

                    } else {

                        $('#errorModal .modal-body').html(responseJSON.error);
                        $('#errorModal').modal('show');
                    }
                }
            },
        });
    },

    appScreenshotUpload:function()
    {
        var appId = $("#appId").val();

        if ($("#app-screenshots-button-1").is(":visible")) {
            this.appScreenshotShow(1);
        }
        if($("#app-screenshots-button-2").is(":visible")) {
            this.appScreenshotShow(2);
        }
        if($("#app-screenshots-button-3").is(":visible")) {
            this.appScreenshotShow(3);
        }
    },

    tagsEditRender: function()
    {
        if ($("#appTags").length) {
            $('#appTags').tagsinput({
    		  maxTags: 3,
    		  tagClass: 'btn btn-warning btn-small'
    		});
        }
    },

    clipboardRender: function()
    {
        if ($("#app-list-page").length) {
            var clipboard = new Clipboard('#app-list-page .app-copy-link');
            clipboard.on('success', function(e) {
                e.clearSelection();
                $(e.trigger).tooltip('show');
            });

        }
    },

	/**
	 * Initializes the view (highlighters, callbacks, etc)
	 */
	initializeView: function(uri)
	{
		Forum._uri = uri;
		Forum.addCallbacks();
        Forum.editorRender();
        //Forum.nodeSelectRender();
        Forum.imageViewer();
        Forum.textAreaResize();
        Forum.appIconUpload();
        Forum.appQrcodeUpload();
        Forum.appScreenshotUpload();
        Forum.tagsEditRender();
        Forum.clipboardRender();

        prettyPrint();
	}
};
