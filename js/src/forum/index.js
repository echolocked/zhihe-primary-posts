import { extend } from 'flarum/common/extend';
import app from 'flarum/forum/app';
import DiscussionPage from 'flarum/forum/components/DiscussionPage';
import Post from 'flarum/forum/components/Post';
import CommentPost from 'flarum/forum/components/CommentPost';
import Button from 'flarum/common/components/Button';
import DiscussionComposer from 'flarum/forum/components/DiscussionComposer';
import ReplyComposer from 'flarum/forum/components/ReplyComposer';
import PostControls from 'flarum/forum/utils/PostControls';

app.initializers.add('zhihe-primary-posts', () => {
  // Add primary filter toggle to sidebar (above scrubber)
  extend(DiscussionPage.prototype, 'sidebarItems', function (items) {
    const discussion = this.discussion;
    if (discussion) {
      // Get current filter state
      const params = new URLSearchParams(window.location.search);
      const currentFilter = params.get('filter') || 'all';
      
      items.add('primaryFilter',
        Button.component({
          className: 'Button Button--block',
          icon: currentFilter === 'primary' ? 'fas fa-bookmark' : 'far fa-bookmark',
          onclick: () => {
            const newFilter = currentFilter === 'all' ? 'primary' : 'all';
            this.toggleFilter(newFilter);
          }
        }, currentFilter === 'primary' 
          ? app.translator.trans('zhihe-primary-posts.forum.all_posts')
          : app.translator.trans('zhihe-primary-posts.forum.primary_only')
        ),
        5  // Just above scrubber
      );
    }
  });

  // Add toggle filter method to DiscussionPage
  extend(DiscussionPage.prototype, 'oninit', function () {
    this.toggleFilter = (filter) => {
      // Update URL with filter parameter
      const url = new URL(window.location);
      if (filter === 'all') {
        url.searchParams.delete('filter');
      } else {
        url.searchParams.set('filter', filter);
      }
      
      // Update URL without page reload
      window.history.replaceState({}, '', url.toString());
      
      // Apply filter to posts
      const posts = document.querySelectorAll('.PostStream-item');
      posts.forEach(postElement => {
        const hasPrimaryBadge = postElement.querySelector('.PrimaryBadge');
        const isPrimary = !!hasPrimaryBadge;
        
        if (filter === 'primary') {
          postElement.style.display = isPrimary ? 'block' : 'none';
        } else {
          postElement.style.display = 'block';
        }
      });
      
      m.redraw();
    };
  });

  // Apply initial filter from URL on page load
  extend(DiscussionPage.prototype, 'oncreate', function () {
    const params = new URLSearchParams(window.location.search);
    const currentFilter = params.get('filter');
    if (currentFilter === 'primary') {
      this.toggleFilter('primary');
    }
  });

  // Add primary post badges to header items (inline in post header)
  extend(CommentPost.prototype, 'headerItems', function (items) {
    const post = this.attrs.post;
    
    if (post && post.attribute('isPrimary')) {
      items.add('primaryBadge',
        m('span', {
          className: 'PrimaryBadge',
          title: app.translator.trans('zhihe-primary-posts.forum.primary_post')
        }, 
          m('i', { className: 'fas fa-bookmark' })
        ),
        80  // Between user (100) and meta (0)
      );
    }
  });

  // Add mark/unmark controls to post dropdown menu (like Edit/Delete)
  extend(PostControls, 'userControls', function (items, post, context) {
    const discussion = post.discussion();
    const user = app.session.user;

    // Show mark/unmark controls only for discussion author (OP)
    if (user && discussion && user.id() === discussion.user().id()) {
      if (post.attribute('isPrimary')) {
        items.add('unmarkPrimary',
          Button.component({
            icon: 'far fa-bookmark',
            onclick: () => {
              context.unmarkAsPrimary();
            }
          }, app.translator.trans('zhihe-primary-posts.forum.unmark_primary')),
          90
        );
      } else {
        items.add('markPrimary',
          Button.component({
            icon: 'fas fa-bookmark',
            onclick: () => {
              context.markAsPrimary();
            }
          }, app.translator.trans('zhihe-primary-posts.forum.mark_primary')),
          90
        );
      }
    }
  });

  // Add mark/unmark methods to Post prototype
  extend(Post.prototype, 'oninit', function () {
    this.markAsPrimary = () => {
      const post = this.attrs.post;
      
      app.request({
        method: 'POST',
        url: app.forum.attribute('apiUrl') + '/posts/' + post.id() + '/mark-primary'
      }).then(() => {
        post.pushAttributes({ isPrimary: true });
        m.redraw();
      }).catch(error => {
        console.error('Failed to mark post as primary:', error);
      });
    };

    this.unmarkAsPrimary = () => {
      const post = this.attrs.post;
      
      app.request({
        method: 'DELETE',
        url: app.forum.attribute('apiUrl') + '/posts/' + post.id() + '/unmark-primary'
      }).then(() => {
        post.pushAttributes({ isPrimary: false, primaryNumber: null });
        m.redraw();
      }).catch(error => {
        console.error('Failed to unmark post as primary:', error);
      });
    };
  });

  // Add "Mark as Primary" checkbox to Discussion Composer
  extend(DiscussionComposer.prototype, 'headerItems', function (items) {
    // Auto-check the checkbox for new discussions
    if (this.composer.fields.isPrimary === undefined) {
      this.composer.fields.isPrimary = true;
    }
    
    items.add('isPrimary',
      m('div', { className: 'Form-group' }, [
        m('label', { className: 'checkbox' }, [
          m('input', {
            type: 'checkbox',
            checked: this.composer.fields.isPrimary || false,
            onchange: (e) => {
              this.composer.fields.isPrimary = e.target.checked;
            }
          }),
          ' ',
          app.translator.trans('zhihe-primary-posts.forum.primary_checkbox')
        ])
      ]),
      10
    );
  });

  // Add "Mark as Primary" checkbox to Reply Composer (only for OP)
  extend(ReplyComposer.prototype, 'headerItems', function (items) {
    const discussion = this.composer.body.attrs.discussion;
    const user = app.session.user;
    
    // Only show for discussion author (OP)
    if (user && discussion && user.id() === discussion.user().id()) {
      items.add('isPrimary',
        m('div', { className: 'Form-group' }, [
          m('label', { className: 'checkbox' }, [
            m('input', {
              type: 'checkbox',
              checked: this.composer.fields.isPrimary || false,
              onchange: (e) => {
                this.composer.fields.isPrimary = e.target.checked;
              }
            }),
            ' ',
            app.translator.trans('zhihe-primary-posts.forum.primary_checkbox')
          ])
        ]),
        10
      );
    }
  });

  // Add isPrimary to discussion creation data
  extend(DiscussionComposer.prototype, 'data', function (data) {
    data.isPrimary = this.composer.fields.isPrimary || false;
  });

  // Add isPrimary to reply creation data
  extend(ReplyComposer.prototype, 'data', function (data) {
    data.isPrimary = this.composer.fields.isPrimary || false;
  });
});