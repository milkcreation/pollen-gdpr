'use strict'

// Dépendances
import tingle from "tingle.js"

// Personnalisation
let gdprPolicyModal = undefined

const
    gdprPolicyModalCreate = (content, options = {}) => {
      Object.assign(options, {
        closeMethods: ['overlay', 'escape'],
        cssClass: ['GdprPolicyModal']
      })

      let modal = new tingle.modal(options)
      modal.setContent(content)

      return modal
    },
    gdprPolicyFetchContent = async (endpoint, request = {}) => {
      Object.assign(request, {
        method: 'GET',
        headers: {
          'Content-type': 'text/html; charset=UTF-8',
          'X-Requested-with': 'XMLHttpRequest'
        }
      });

      try {
        let response = await fetch(endpoint, request) || undefined

        if (response === undefined) {
          return ''
        }

        if (response.ok) {
          return await response.text()
        } else {
          console.log(response.status)
        }
      } catch (e) {
        throw e
      }

      return ''
    },
    gdprPolicyModalOpen = endpoint => {
      if (gdprPolicyModal === undefined) {
          gdprPolicyFetchContent(endpoint).then(content => {
            gdprPolicyModal = gdprPolicyModalCreate(content);
            gdprPolicyModal.open();
          })
      } else {
        gdprPolicyModal.open();
      }
    }

export {gdprPolicyModalOpen};