'use strict'

import policy from './gdpr-policy'

const gdprBannerGetFetchArgsFromURI = (URI) => {
      let request = {}

      try {
        const decodedURI = decodeURIComponent(URI)
        request = JSON.parse(decodedURI)
      } catch (e) {
        throw e
      }

      const endpoint = request.endpoint
      delete request.endpoint

      if (request.body) {
        try {
          request.body = JSON.stringify(request.body)
        } catch (e) {
          throw e
        }
      }

      return [endpoint, request];
    },

    gdprBannerGetDefered = async (endpoint, request) => {
      try {
        let response = await fetch(endpoint, request)

        if (response.ok) {
          return await response.text()
        } else {
          console.log(response.status)
        }
      } catch (e) {
        console.log(e)
      }

      return ''
    },

    gdprBannerAcceptCookies = async (endpoint, request) => {
      try {
        let response = await fetch(endpoint, request)

        if (response.ok) {
          return await response.json()
        } else {
          console.log(response.status)
        }
      } catch (e) {
        console.log(e)
      }

      return {success: false}
    }

window.addEventListener('load', () => {
  const $deferContainer = document.querySelector('.GdprBanner-defer')

  if ($deferContainer === null) {
    return
  }

  const uri = $deferContainer.dataset.request
  if (uri === undefined) {
    return
  }

  let args = []
  try {
    args = gdprBannerGetFetchArgsFromURI(uri);
  } catch (e) {
    console.log(e)
    return
  }

  gdprBannerGetDefered(...args).then((html) => {
    if (!html) {
      return
    }

    $deferContainer.innerHTML = html
    const $gdprBanner = document.querySelector('.GdprBanner')
    if ($gdprBanner === null) {
      return
    }

    const $policyButton = $gdprBanner.querySelector('.GdprBanner-policy')
    if ($policyButton) {
      policy($policyButton)
    }

    $gdprBanner.setAttribute('aria-hide', 'false')

    const $closeHandler = $gdprBanner.querySelector('.GdprBanner-close')
    if ($closeHandler !== null) {
      $closeHandler.addEventListener('click', (e) => {
        e.preventDefault()

        $gdprBanner.classList.remove('visible')
        $gdprBanner.setAttribute('aria-hide', 'true')
      })
    }

    const $acceptHandler = $gdprBanner.querySelector('.GdprBanner-accept')
    if ($acceptHandler !== null) {
      $acceptHandler.addEventListener('click', (e) => {
        e.preventDefault()

        const uri = $gdprBanner.dataset.request
        if (uri === undefined) {
          return
        }

        let args = [];
        try {
          args = gdprBannerGetFetchArgsFromURI(uri);
        } catch (e) {
          console.log(e)
          return
        }

        gdprBannerAcceptCookies(...args).then((data) => {
          if (data.success === true) {
            $gdprBanner.classList.remove('visible')
            $gdprBanner.setAttribute('aria-hide', 'true')
          }
        })
      })
    }

    setTimeout(() => {
      $gdprBanner.classList.add('visible')
    }, 10)
  })
})