import { moduleMetadata } from '@storybook/angular';
import { CommonModule } from '@angular/common';
// @ts-ignore
import { Story, Meta } from '@storybook/angular/types-6-0';

import * as HeaderStories from './Header.stories';
import { ButtonComponent } from '../app/shared/ui/component/button/button.component';
import { YesNavComponent } from '../app/navigation/ui/yes-nav/yes-nav.component';
import { PageComponent } from '../app/navigation/ui/page/page.component';
import { ReactiveFormsModule } from '@angular/forms';
import { MatIconModule } from '@angular/material/icon';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

export default {
  title: 'Assembly/Page',
  component: YesNavComponent,
  decorators: [
    moduleMetadata({
      declarations: [ButtonComponent, YesNavComponent],
      imports: [
        CommonModule,
        ReactiveFormsModule,
        MatIconModule,
        MatFormFieldModule,
        MatInputModule,
        BrowserAnimationsModule],
    }),
  ],
} as Meta;

const Template: Story<PageComponent> = (args: PageComponent) => ({
  component: PageComponent,
  props: args,
});

export const LoggedIn = Template.bind({});
LoggedIn.args = {
  ...HeaderStories.LoggedIn.args,
};

export const LoggedOut = Template.bind({});
LoggedOut.args = {
  ...HeaderStories.LoggedOut.args,
};
