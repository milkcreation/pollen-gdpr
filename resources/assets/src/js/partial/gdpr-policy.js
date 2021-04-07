'use strict'

import { gdprPolicyModalOpen } from '../gdpr-policy-modal'

const gdprPolicyLink = $link => {
  $link.addEventListener('click', e => {
    e.preventDefault()
    gdprPolicyModalOpen($link.getAttribute('href'))
  })
}

window.addEventListener('load', () => {
  const $gdprPolicyLinks = document.querySelectorAll('[data-gdpr="policy"]')

  if ($gdprPolicyLinks) {
    $gdprPolicyLinks.forEach($link => {
      gdprPolicyLink($link)
    })
  }
})

export default gdprPolicyLink