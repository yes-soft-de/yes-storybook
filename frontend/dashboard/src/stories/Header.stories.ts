import { moduleMetadata } from '@storybook/angular';
import { CommonModule } from '@angular/common';
// @ts-ignore
import { Story, Meta } from '@storybook/angular/types-6-0';

import {ButtonComponent} from '../app/shared/ui/component/button/button.component';
import { YesNavComponent } from '../app/navigation/ui/yes-nav/yes-nav.component';

export default {
  title: 'Navigation/Header',
  component: YesNavComponent,
  decorators: [
    moduleMetadata({
      declarations: [ButtonComponent],
      imports: [CommonModule],
    }),
  ],
} as Meta;

const Template: Story<YesNavComponent> = (args: YesNavComponent) => ({
  component: YesNavComponent,
  props: args,
});

export const LoggedIn = Template.bind({});
LoggedIn.args = {
  user: undefined,
};

export const LoggedOut = Template.bind({});
LoggedOut.args = {
  user: {
    firstName: 'Mohammad',
    lastName: 'Al Kalaleeb',
    image: 'https://images.pexels.com/photos/6850336/pexels-photo-6850336.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500'
  }
};
