'use strict'

window.addEventListener('load', () => {
  const $deferContainer = document.querySelector('.CookieBanner-defer'),
      getFetchArgsFromURI = (URI) => {
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

      getDeferedCookieBanner = async (endpoint, request) => {
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

      acceptCookies = async (endpoint, request) => {
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

  if ($deferContainer === null) {
    return
  }

  const uri = $deferContainer.dataset.request
  if (uri === undefined) {
    return
  }

  let args = []
  try {
    args = getFetchArgsFromURI(uri);
  } catch (e) {
    console.log(e)
    return
  }

  getDeferedCookieBanner(...args).then((html) => {
    if (!html) {
      return
    }

    $deferContainer.innerHTML = html
    const $cookieBanner = document.querySelector('.CookieBanner')
    if ($cookieBanner === null) {
      return
    }

    $cookieBanner.setAttribute('aria-hide', 'false')

    const $closeHandler = $cookieBanner.querySelector('.CookieBanner-close')
    if ($closeHandler !== null) {
      $closeHandler.addEventListener('click', (e) => {
        e.preventDefault()

        $cookieBanner.classList.remove('visible')
        $cookieBanner.setAttribute('aria-hide', 'true')
      })
    }

    const $acceptHandler = $cookieBanner.querySelector('.CookieBanner-accept')
    if ($acceptHandler !== null) {
      $acceptHandler.addEventListener('click', (e) => {
        e.preventDefault()

        const uri = $cookieBanner.dataset.request
        if (uri === undefined) {
          return
        }

        let args = [];
        try {
          args = getFetchArgsFromURI(uri);
        } catch (e) {
          console.log(e)
          return
        }

        acceptCookies(...args).then((data) => {
          if (data.success === true) {
            $cookieBanner.classList.remove('visible')
            $cookieBanner.setAttribute('aria-hide', 'true')
          }
        })
      })
    }

    setTimeout(() => {
      $cookieBanner.classList.add('visible')
    }, 1000)
  })
})