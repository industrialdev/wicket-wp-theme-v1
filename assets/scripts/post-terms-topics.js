// Modifies the tags inside the post term/topics core Block, because we don't want them clickable, just listed
window.addEventListener('load', function () {
  if (document.querySelector('body') !== null) {
    document.querySelectorAll('.wp-block-post-terms a').forEach(oldTag => {
      let newTag = document.createElement('span');
      newTag.innerHTML = oldTag.innerHTML;

      oldTag.parentNode.replaceChild(newTag, oldTag);
    });
  }
});